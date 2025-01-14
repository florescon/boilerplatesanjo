<?php

namespace App\Http\Livewire\Backend\Chart\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use App\Models\Status;
use DB;
use Symfony\Component\HttpFoundation\Response;
use Excel;
use App\Exports\OrderByDateExport;

class OrderTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'history' => ['except' => FALSE],
    ];

    public $title = [];

    public $perPage = '10';

    public $byYear = '2025';
    public $byMonth = 'Enero';

    public $limitPerPage = '50';

    public $sortField = 'created_at';
    public $sortAsc = false;

    public $status;
    public $searchTerm = '';

    public bool $history = false;

    public $dateInput = '';
    public $dateOutput = '';

    public $nameStatus;
    public ?int $statusOrder = null;

    public $selectedtypes = [];

    protected $listeners = ['selectedStatusOrderItem'];

    protected $messages = [
        'selectedtypes.max' => 'Máximo 30 registros a seleccionar.',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    private function getSelectedProducts()
    {
        return $this->selectedtypes;
    }

    public function productGrouped()
    {
        $this->validate([
            'selectedtypes' => 'max:30',
        ]);

        $ordercollection = collect();

        foreach($this->getSelectedProducts() as $orderID){
            $order = Order::find($orderID);

            $ordercollection->push(
                $order->id,
            );
        }

        return redirect()->route('admin.order.printexportorders', urlencode(json_encode($ordercollection)));
    }

    public function printExportOrdersForDate()
    {   
        $extension = 'xlsx';

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new OrderByDateExport($this->dateInput, $this->dateOutput, true), 'product-list-'.Carbon::now().'.'.$extension);
    }

    public function getRowsQueryProperty()
    {
        $lastProcessId = Order::getLastProcess()->id;

        $query = Order::query()
        ->with([
            'product_order.product_station_received',
            'product_order.product_station_out',
            'user',
            'products',
            'product_order',
            'last_status_order.status',
        ])        
            // ->onlyAssignment(6)
        ->when($this->dateInput, function ($query) {
            empty($this->dateOutput) ?
            $query->whereBetween('created_at', [$this->dateInput.' 00:00:00', now()]) :
            $query->whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
        })
        ->when(!$this->history, function ($query) use ($lastProcessId) {
            if ($this->status != 'quotations') {
                $query->where(function ($query) use ($lastProcessId) {
                    $query->whereHas('product_order', function ($query) use ($lastProcessId) {
                        $query
                        ->whereDoesntHave('product_station_received', function ($query) use ($lastProcessId) {
                            $query->havingRaw('SUM(quantity) >= product_order.quantity')->where('status_id', $lastProcessId);
                        })
                        ->whereDoesntHave('product_station_out', function ($query) {
                            $query->havingRaw('SUM(out_quantity) >= product_order.quantity');
                        })
                        ;
                    });

                });
            }
        })
        // ->when(!$this->dateInput, function ($query) {
        //     $query->whereYear('created_at', now()->year);
        // })
        ->when($this->sortField, function ($query) {
            $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
        });

        if ($this->status === 'deleted') {
            $this->applySearchDeletedFilter($query);

            $this->title = ['title' => 'Deleted orders', 'color' => 'danger'];
            return $query->flowchart()->onlyTrashed();
        }
        else{

            if ($this->status === 'suborders') {
                $this->title = ['title' => 'List of outputs', 'color' => 'secondary'];
                return $query->onlySuborders()->outFromStore()->flowchart();
            }
            if ($this->status === 'quotations') {
                $this->applySearchFilter($query);
                $this->title = ['title' => 'List of quotations', 'color' => '#FAFA33'];
                return $query->onlyQuotations()->outFromStore()->flowchart();
            }
            if ($this->status === 'sales') {
                $this->title = ['title' => 'List of sales', 'color' => 'success'];
                return $query->onlySales()->outFromStore()->flowchart();
            }
            if ($this->status === 'mix') {
                $this->title = ['title' => 'List of mix', 'color' => 'warning'];
                return $query->onlyMix()->outFromStore()->flowchart();
            }
            if ($this->status === 'all') {
                $this->applySearchFilter($query);
                $this->title = ['title' => 'List of all', 'color' => 'dark'];
                return $query->onlyAll()->outFromStore()->flowchart();
            }

            $this->applySearchFilter($query);
        }

        $this->title = ['title' => 'List of orders', 'color' => 'primary'];

        return $query->onlyOrders()->outFromStore()->flowchart();
    }

    private function applySearchFilter($orders)
    {
        if ($this->searchTerm) {

            return $orders->flowchart()->whereHas('user', function ($query) {
                $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
            })
            ->orWhereHas('departament', function ($query) {
                $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
            })
            ->orWhere('folio', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('info_customer', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('request', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('purchase', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('quotation', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('invoice', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('comment', 'like', '%' . $this->searchTerm . '%');
        }

        return null;
    }

    public function selectedStatusOrderItem(?int $item)
    {
        if ($item){
            $this->resetPage();
            $this->statusOrder = $item;
        }
        else{
            $this->statusOrder = null;
        }
    }

    public function clearFilterStatusOrder()
    {
        $this->statusOrder = null;
        $this->nameStatus = null;
        $this->resetPage();
        $this->emit('clear-status-order');
    }

    private function applySearchDeletedFilter($orders)
    {
        if ($this->searchTerm) {
            return $orders->whereRaw("id LIKE \"%$this->searchTerm%\"")
            ->orWhereRaw("slug LIKE \"%$this->searchTerm%\"");

        }

        return null;
    }

    public function isHistory()
    {
        $this->resetPage();
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->currentMonth = FALSE;
        $this->currentWeek = FALSE;

        if($this->history){
            $this->history = FALSE;
        }
        else{
            $this->history = TRUE;
        }
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate(($this->perPage > $this->limitPerPage) ? $this->clear() : $this->perPage);
        });
    }

    public function clearFilterDate()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '10';
    }

    public function clearAll()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '10';
        $this->emit('clear-status-order');
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedDateInput()
    {
        $this->resetPage();
    }

    public function updatedDateOutput()
    {
        $this->resetPage();
    }

    public function render()
    {

        return view('backend.chart.order.order-table', [
          'orders' => $this->rows,
      ]);
    }}

<?php

namespace App\Http\Livewire\Backend\Order;

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
    ];

    public $title = [];

    public $perPage = '10';

    public $limitPerPage = '100';

    public $sortField = 'created_at';
    public $sortAsc = false;

    public $status;
    public $searchTerm = '';

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
        return Excel::download(new OrderByDateExport($this->dateInput, $this->dateOutput, false), 'product-list-'.Carbon::now().'.'.$extension);
    }

    public function getRowsQueryProperty()
    {
        $query = Order::query()->with('user', 'products', 'last_status_order.status')
            // ->onlyAssignment(6)
        ->when($this->dateInput, function ($query) {
            empty($this->dateOutput) ?
            $query->whereBetween('created_at', [$this->dateInput.' 00:00:00', now()]) :
            $query->whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
        })
        ->when($this->statusOrder, function ($query) {

            $status = Status::findOrFail($this->statusOrder);

            $this->nameStatus = $status->name ? '— '.$status->name : '';

            $statusOrder = $this->statusOrder;
            $query->whereHas('last_status_order.status', function($queryStatusOrder) use ($statusOrder){
                $queryStatusOrder->where('id', $statusOrder);
            });
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
            return $query->withoutFlowchart()->onlyTrashed();
        }
        else{

            if ($this->status === 'suborders') {
                $this->applySearchFilter($query);

                $this->title = ['title' => 'List of outputs', 'color' => 'secondary'];
                return $query->onlySuborders()->outFromStore()->withoutFlowchart();
            }
            if ($this->status === 'quotations') {
                $this->applySearchFilter($query);
                $this->title = ['title' => 'List of quotations', 'color' => '#FAFA33'];
                return $query->onlyQuotations()->outFromStore()->withoutFlowchart();
            }
            if ($this->status === 'sales') {
                $this->applySearchFilter($query);
                $this->title = ['title' => 'List of sales', 'color' => 'success'];
                return $query->onlySales()->outFromStore()->withoutFlowchart();
            }
            if ($this->status === 'mix') {
                $this->applySearchFilter($query);
                $this->title = ['title' => 'List of mix', 'color' => 'warning'];
                return $query->onlyMix()->outFromStore()->withoutFlowchart();
            }
            if ($this->status === 'all') {
                $this->applySearchFilter($query);
                $this->title = ['title' => 'List of all', 'color' => 'dark'];
                return $query->onlyAll()->outFromStore()->withoutFlowchart();
            }

            $this->applySearchFilter($query);
        }

        $this->title = ['title' => 'List of orders', 'color' => 'primary'];

        return $query->onlyOrders()->outFromStore()->withoutFlowchart();
    }

    private function applySearchFilter($orders)
    {
        if ($this->searchTerm) {

            return $orders->withoutFlowchart()->whereHas('user', function ($query) {
                $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
            })
            ->orWhereHas('departament', function ($query) {
                $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
            })
            ->orWhere('folio', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('info_customer', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('request', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('quotation', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('purchase', 'like', '%' . $this->searchTerm . '%')
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
            // return $orders->whereRaw("id LIKE \"%$this->searchTerm%\"")
            // ->orWhereRaw("slug LIKE \"%$this->searchTerm%\"");

            return $orders->whereHas('user', function ($query) {
                $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
            })
            ->orWhereHas('departament', function ($query) {
                $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
            })
            ->orWhere('folio', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('info_customer', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('request', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('purchase', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('invoice', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('comment', 'like', '%' . $this->searchTerm . '%');
        }

        return null;
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
        $this->perPage = '5';
    }

    public function clearAll()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '5';
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

        // $orders = Order::query()->withTrashed()->get();

        // foreach($orders as $order){
        //     $si = DB::table('orders')
        //       ->where('id', $order->id)
        //       ->update(['folio' => $order->id]);
        // }

        return view('backend.order.table.order-table', [
          'orders' => $this->rows,
      ]);
    }
}

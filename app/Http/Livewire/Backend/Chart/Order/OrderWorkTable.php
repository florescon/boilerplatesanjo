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
use App\Exports\OrderProcessExport;
use App\Exports\OrderByDateExport;

class OrderWorkTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
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

    public $lastProcessId;

    // Método de ciclo de vida de Livewire, se ejecuta cuando el componente se monta
    public function mount()
    {
        // Asignar el id del último proceso a la propiedad pública
        $this->lastProcessId = Order::getLastProcess()->id;
    }

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
        $lastProcessId = $this->lastProcessId;

        $query = Order::query()
        ->with([
            'product_order.product_station_received',
            'product_order.product_station_out',
            'user',
            'productionBatches',
            'products',
            'last_status_order.status',
        ])        
            // ->onlyAssignment(6)
        ->doesntHave('stations') // <- Solo órdenes con al menos una estación
        ->when($this->dateInput || !$this->dateInput, function ($query) {
            $minAllowedDate = '2025-04-01 00:00:00'; // Fecha mínima (posterior al 14/05/2025)
            
            // Caso 1: Si hay dateInput, usamos la mayor entre dateInput y minAllowedDate
            if ($this->dateInput) {
                $startDate = max(
                    Carbon::parse($this->dateInput)->startOfDay(),
                    Carbon::parse($minAllowedDate)
                )->toDateTimeString();
            } 
            // Caso 2: Si NO hay dateInput, forzamos minAllowedDate
            else {
                $startDate = $minAllowedDate;
            }

            // Lógica para endDate (igual que antes)
            $endDate = $this->dateOutput 
                ? Carbon::parse($this->dateOutput)->endOfDay()->toDateTimeString()
                : null;

            $query->where('created_at', '>=', $startDate);
            
            if ($endDate) {
                $query->where('created_at', '<=', $endDate);
            }
        })

        ->when($this->history === false, function ($query) {
            // Subconsulta para identificar órdenes que NO cumplen validateAllExists()
            $query->where(function($q) {
                $q->whereRaw("(
                    SELECT COALESCE(SUM(pbi.input_quantity), 0) 
                    FROM production_batch_items pbi
                    JOIN production_batches pb ON pb.id = pbi.batch_id
                    WHERE pb.order_id = orders.id
                    AND pbi.is_principal = 1
                    AND pbi.with_previous IS NULL
                ) != (
                    SELECT COALESCE(SUM(po.quantity), 0)
                    FROM product_order po
                    JOIN products p ON p.id = po.product_id
                    WHERE po.order_id = orders.id
                    AND p.type = 1
                )")
                ->orWhereRaw("EXISTS (
                    SELECT 1 
                    FROM production_batch_items pbi
                    JOIN production_batches pb ON pb.id = pbi.batch_id
                    WHERE pb.order_id = orders.id
                    AND pbi.active != 0
                )");
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
            return $orders->where(function($query) {
                $query->whereHas('user', function ($q) {
                    $q->whereRaw("name LIKE \"%$this->searchTerm%\"");
                })
                ->orWhereHas('departament', function ($q) {
                    $q->whereRaw("name LIKE \"%$this->searchTerm%\"");
                })
                ->orWhere('folio', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('info_customer', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('request', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('purchase', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('quotation', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('invoice', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('comment', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $orders;
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

    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new OrderProcessExport, 'order-process-'.Carbon::now().'.'.$extension);
    }

    private function applySearchDeletedFilter($orders)
    {
        if ($this->searchTerm) {
            return $orders->where(function($query) {
                $query->whereHas('user', function ($q) {
                    $q->whereRaw("name LIKE \"%$this->searchTerm%\"");
                })
                ->orWhereHas('departament', function ($q) {
                    $q->whereRaw("name LIKE \"%$this->searchTerm%\"");
                })
                ->orWhere('folio', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('info_customer', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('request', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('purchase', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('quotation', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('invoice', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('comment', 'like', '%' . $this->searchTerm . '%');
            });
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
    }
}

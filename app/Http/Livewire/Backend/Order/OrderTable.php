<?php

namespace App\Http\Livewire\Backend\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;

class OrderTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    public $title = [];

    public $perPage = '5';

    public $limitPerPage = '100';

    public $sortField = 'id';
    public $sortAsc = false;

    public $status;
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

    public ?int $statusOrder = null;

    protected $listeners = ['selectedStatusOrderItem'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function getRowsQueryProperty()
    {
        $query = Order::query()->with('user', 'last_status_order.status')
            // ->onlyAssignment(6)
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', now()]) :
                $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
            })
            ->when($this->statusOrder, function ($query) {

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
            return $query->onlyTrashed();
        }
        else{
            $this->applySearchFilter($query);

            if ($this->status === 'suborders') {
                $this->title = ['title' => 'List of suborder outputs', 'color' => 'secondary'];
                return $query->onlySuborders()->outFromStore();
            }
            if ($this->status === 'quotations') {
                $this->title = ['title' => 'List of quotations', 'color' => '#FAFA33'];
                return $query->onlyQuotations()->outFromStore();
            }
            if ($this->status === 'sales') {
                $this->title = ['title' => 'List of sales', 'color' => 'success'];
                return $query->onlySales()->outFromStore();
            }
            if ($this->status === 'mix') {
                $this->title = ['title' => 'List of mix', 'color' => 'warning'];
                return $query->onlyMix()->outFromStore();
            }
            if ($this->status === 'all') {
                $this->title = ['title' => 'List of all', 'color' => 'dark'];
                return $query->onlyAll()->outFromStore();
            }
        }

        $this->title = ['title' => 'List of orders', 'color' => 'primary'];

        return $query->onlyOrders()->outFromStore();
    }

    private function applySearchFilter($orders)
    {
        if ($this->searchTerm) {

            return $orders->whereHas('user', function ($query) {
               $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
            })
            ->orWhereHas('departament', function ($query) {
               $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
            })
            ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('info_customer', 'like', '%' . $this->searchTerm . '%')
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
        return view('backend.order.table.order-table', [
          'orders' => $this->rows,
        ]);
    }
}
<?php

namespace App\Http\Livewire\Backend\Store;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;

class OrderStoreTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    public $title = [];

    public $perPage = '12';

    public $sortField = 'id';
    public $sortAsc = false;

    public $status;
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

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
        $query = Order::query()->with('user', 'orders_payments', 'orders_delivery', 'last_order_delivery', 'product_order', 'product_sale', 'product_suborder', 'last_status_order.status')
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', now()]) :
                $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
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

            // if ($this->status === 'orders_store') {
            //     $this->title = ['title' => 'List of orders', 'color' => 'primary'];
            //     return $query->onlyOrders()->onlyFromStore();
            // }
            if ($this->status === 'requests_store') {
                $this->title = ['title' => 'List of requests', 'color' => 'coral'];
                return $query->onlyRequests()->onlyFromStore();
            }
            if ($this->status === 'output_products_store') {
                $this->title = ['title' => 'List of output products', 'color' => 'coral'];
                return $query->onlyOutputProducts()->onlyFromStore();
            }
            if ($this->status === 'quotations_store') {
                $this->title = ['title' => 'List of quotations', 'color' => '#FAFA33'];
                return $query->onlyQuotations()->onlyFromStore();
            }
            if ($this->status === 'sales_store') {
                $this->title = ['title' => 'List of sales', 'color' => 'success'];
                return $query->onlySales()->onlyFromStore();
            }
            // if ($this->status === 'mix_store') {
            //     $this->title = ['title' => 'List of mix', 'color' => 'warning'];
            //     return $query->onlyMix()->onlyFromStore();
            // }
            if ($this->status === 'all_store') {
                $this->title = ['title' => 'List of all', 'color' => 'dark'];
                return $query->onlyFromStore();
            }
        }

        $this->title = ['title' => 'List of orders', 'color' => 'primary'];

        return $query->onlyFromStore();
    }

    private function applySearchFilter($orders)
    {
        if ($this->searchTerm) {
            return $orders->whereRaw("id LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("comment LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("info_customer LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("slug LIKE \"%$this->searchTerm%\"");
        }

        return null;
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
            return $this->rowsQuery->paginate($this->perPage);
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
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedDateInput()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('backend.store.table.order-store-table', [
          'orders' => $this->rows,
        ]);
    }
}

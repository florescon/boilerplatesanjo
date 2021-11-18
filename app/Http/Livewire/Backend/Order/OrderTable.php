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

    public $perPage = '10';

    public $sortField = 'updated_at';
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
        $query = Order::query()->with('user', 'last_status_order.status')
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', now()]) :
                $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
            })
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            });


        if ($this->status === 'deleted') {
            $this->title = ['title' => 'Deleted orders', 'color' => 'danger'];
            return $query->onlyTrashed();
        }
        if ($this->status === 'suborders') {
            $this->title = ['title' => 'List of suborders', 'color' => 'info'];
            return $query->onlySuborders();
        }
        if ($this->status === 'sales') {
            $this->title = ['title' => 'List of sales', 'color' => 'success'];
            return $query->onlySales();
        }
        if ($this->status === 'mix') {
            $this->title = ['title' => 'List of mix', 'color' => 'warning'];
            return $query->onlyMix();
        }
        if ($this->status === 'all') {
            $this->title = ['title' => 'List of all', 'color' => 'dark'];
            return $query->onlyAll();
        }

        $this->title = ['title' => 'List of orders', 'color' => 'primary'];

        $this->applySearchFilter($query);

        return $query->onlyOrders();
    }


    private function applySearchFilter($orders)
    {
        if ($this->searchTerm) {
            return $orders->whereRaw("comment LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("id LIKE \"%$this->searchTerm%\"");

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
        $this->page = 1;
        $this->perPage = '10';
    }

    public function clearAll()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->searchTerm = '';
        $this->page = 1;
        $this->perPage = '10';
    }

    public function render()
    {
        return view('backend.order.table.order-table', [
          'orders' => $this->rows,
        ]);
    }
}

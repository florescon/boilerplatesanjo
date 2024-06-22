<?php

namespace App\Http\Livewire\Backend\Information;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;

class Chart extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 5;

    public $sortField = 'created_at';
    public $sortAsc = false;

    public function getRowsQueryProperty()
    {
        $lastProcessId = Order::getLastProcess()->id;

        $orders = Order::with('product_order.product_station_received', 'product_order.product_station_out')
                    ->where(function ($query) use ($lastProcessId) {
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
                    })
                    ->when($this->sortField, function ($query) {
                        $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
                    })
                    ->onlyOrders()
                    ->outFromStore()
                    ->flowchart();

        return $orders;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate(2);
        });
    }

    public function render()
    {
        return view('backend.information.livewire.chart',[
            'orders' => $this->rows,
        ]);
    }
}

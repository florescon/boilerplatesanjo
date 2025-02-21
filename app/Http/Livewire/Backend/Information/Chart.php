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
                        $query->where(function ($query) use ($lastProcessId) {
                            $query->whereRaw("
                                EXISTS (
                                    SELECT 1
                                    FROM product_order po
                                    LEFT JOIN (
                                        SELECT product_order_id, SUM(quantity) as total_received
                                        FROM product_station_receiveds
                                        WHERE status_id = ?
                                        GROUP BY product_order_id
                                    ) psr ON po.id = psr.product_order_id
                                    LEFT JOIN (
                                        SELECT product_order_id, SUM(out_quantity) as total_out
                                        FROM product_station_outs
                                        GROUP BY product_order_id
                                    ) pso ON po.id = pso.product_order_id
                                    WHERE po.order_id = orders.id
                                    AND (psr.total_received IS NULL OR psr.total_received < po.quantity)
                                    AND (pso.total_out IS NULL OR pso.total_out < po.quantity)
                                )
                            ", [$lastProcessId]);
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

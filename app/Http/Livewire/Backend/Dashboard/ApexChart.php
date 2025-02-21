<?php

namespace App\Http\Livewire\Backend\Dashboard;

use Livewire\Component;
use App\Models\Order;
use App\Models\Status;

class ApexChart extends Component
{
    public $labels = [];
    public $series = [];

    public function render()
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
                    ->onlyOrders()
                    ->outFromStore()
                    ->flowchart()
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();


        $ordercollection = collect();
        $coll = collect();
        $ids = collect();

        foreach($orders as $order){

            $coll[] = $order->total_graphic_new['collection'];

            $ordercollection->push(
                 ['#'.$order->folio_or_id_clear.' —— '.optional($order->user)->real_name, $order->comment],
            );

            $ids->push(
                 $order->id,
            );
        }

        $statuses = Status::orderBy('level')->where('active', TRUE)->get();
        $s = $statuses->pluck('short_name');
        $s->push('captura');

        $this->series = $s->map(function ($label) use ($coll) {
            return [
                'name' => $label,
                'data' => $coll->pluck($label)->map(function ($value) {
                    return $value !== null ? (int)$value : null;
                })->toArray()
            ];
        })->toArray();

        return view('backend.dashboard.livewire.apex-chart', [
            'orders' => $orders,
            'ordercollection' => $ordercollection,
            'ids' => $ids,
        ]);
    }
}

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

        $orders = Order::query()
            ->with([
                'product_order.product_station_received',
                'product_order.product_station_out',
                'user',
                'productionBatches',
                'products',
                'last_status_order.status',
            ])        
            ->doesntHave('stations') // <- Solo órdenes con al menos una estación
            ->where(function($q) {
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
            })
            ->onlyOrders()
            ->outFromStore()
            ->flowchart()
            ->orderBy('folio', 'desc')
            ->limit(10)
            ->get();


        $ordercollection = collect();
        $coll = collect();
        $ids = collect();

        foreach($orders as $order){

            $coll[] = $order->total_graphic_work['collection'];

            $ordercollection->push(
                 ['#'.$order->folio_or_id_clear.' —— '.optional($order->user)->real_name, $order->comment],
            );

            $ids->push(
                 $order->id,
            );
        }

        $statuses = Status::orderBy('level')->where('active', TRUE)->get();
        $s = $statuses->pluck('short_name');
        $s->prepend('captura');

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

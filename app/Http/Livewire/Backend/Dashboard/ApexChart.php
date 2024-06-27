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

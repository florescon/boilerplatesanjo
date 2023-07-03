<?php

namespace App\Http\Livewire\Backend\Dashboard;

use Livewire\Component;
use App\Models\Order;

class Apex extends Component
{
    public function render()
    {
        $orders = Order::orderByDesc('id')->where('type', 1)->take(5)->get();

        $ordercollection = collect();

        foreach($orders as $order){

            $ordercollection->push(
                // 'id' => $order->id,
                // 'folio' => $order->folio,
                 optional($order->user)->name,
                // 'type' => $order->characters_type_order,
                // 'comment' => $order->comment,
            );
        }

        // $us = collect([2008, 2009, 2010, 2011, 2012, 2013, 2014, 214313]);
        // dd($us);

        // dd($ordercollection);

        $statuses = \App\Models\Order::getStatuses();

        $categories = $ordercollection;

        // dd($statuses->sort());

        // dd($order->total_graphic->sortKeys()->keys());        

        return view('backend.dashboard.livewire.apex', [
            'categories' => $categories,
        ]);
    }
}

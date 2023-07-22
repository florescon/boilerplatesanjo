<?php

namespace App\Http\Livewire\Backend\Dashboard;

use Livewire\Component;
use App\Models\Order;

class Apex extends Component
{
    public function render()
    {
        $orders = Order::orderByDesc('id')->with('product_order.product', 'batches_main.batch_product', 'user')->has('batches_main')->limit(10)->get();

        $ordercollection = collect();
        $coll = collect();

        foreach($orders as $order){

            $coll[] = $order->total_graphic;

            $ordercollection->push(
                // 'id' => $order->id,
                // 'folio' => $order->folio,
                 '#'.$order->folio_or_id_clear.' '.optional($order->user)->name.' - '.$order->comment,
                // 'type' => $order->characters_type_order,
                // 'comment' => $order->comment,
            );
        }

        // dd($coll);

        $captura = $coll->map(function ($mapCol) {
                        return $mapCol->only(['captura']);
                    })->flatten();

        $corte = $coll->map(function ($mapCol) {
                        return $mapCol->only(['corte']);
                    })->flatten();

        // dd($corte);

        $confeccion = $coll->map(function ($mapCol) {
                        return $mapCol->only(['confeccion']);
                    })->flatten();

        $conformado = $coll->map(function ($mapCol) {
                        return $mapCol->only(['conformado']);
                    })->flatten();

        $personalizacion = $coll->map(function ($mapCol) {
                        return $mapCol->only(['personalizacion']);
                    })->flatten();

        $embarque = $coll->map(function ($mapCol) {
                        return $mapCol->only(['embarque']);
                    })->flatten();

        // $us = collect([2008, 2009, 2010, 2011, 2012, 2013, 2014, 214313]);
        // dd($us);

        // dd($ordercollection);

        $statuses = \App\Models\Order::getStatuses();

        $categories = $ordercollection;

        // dd($statuses->sort());

        // dd($order->total_graphic->sortKeys()->keys());        

        return view('backend.dashboard.livewire.apex', [
            'categories' => $categories,
            'captura' => $captura->values(),
            'corte' => $corte->values(),
            'confeccion' => $confeccion->values(),
            'conformado' => $conformado->values(),
            'personalizacion' => $personalizacion->values(),
            'embarque' => $embarque->values(),
        ]);
    }
}

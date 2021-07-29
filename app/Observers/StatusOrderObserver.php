<?php

namespace App\Observers;

use App\Models\StatusOrder;
use App\Models\Order;

class StatusOrderObserver
{
    /**
     * Handle the StatusOrder "created" event.
     *
     * @param  \App\Models\StatusOrder  $statusOrder
     * @return void
     */
    public function created(StatusOrder $statusOrder)
    {
        if($statusOrder->status->level >= 0){
            $statusOrder->order()->update(['approved'=> true]);

            $order = $statusOrder->order;

            if($order->materials_order()->doesntExist()){

                foreach($order->product_order as $product_order){

                    foreach($product_order->gettAllConsumption() as $key => $consumption){
                        // dd($consumption['material']);
                        // dd($product_order->id);
                        $order->materials_order()->create([
                            'product_order_id' => $product_order->id,
                            'material_id' => $key,
                            'price' => $consumption['price'],
                            'unit_quantity' => $consumption['unit'],
                            'quantity' => $consumption['quantity'],
                        ]);                
                    }
                }
            }
        }
    }

    /**
     * Handle the StatusOrder "updated" event.
     *
     * @param  \App\Models\StatusOrder  $statusOrder
     * @return void
     */
    public function updated(StatusOrder $statusOrder)
    {
        //
    }

    /**
     * Handle the StatusOrder "deleted" event.
     *
     * @param  \App\Models\StatusOrder  $statusOrder
     * @return void
     */
    public function deleted(StatusOrder $statusOrder)
    {
        //
    }

    /**
     * Handle the StatusOrder "restored" event.
     *
     * @param  \App\Models\StatusOrder  $statusOrder
     * @return void
     */
    public function restored(StatusOrder $statusOrder)
    {
        //
    }

    /**
     * Handle the StatusOrder "force deleted" event.
     *
     * @param  \App\Models\StatusOrder  $statusOrder
     * @return void
     */
    public function forceDeleted(StatusOrder $statusOrder)
    {
        //
    }
}

<?php

namespace App\Observers;

use App\Models\ProductOrder;
use App\Models\Product;

class ProductOrderObserver
{
    /**
     * Handle the ProductOrder "created" event.
     *
     * @param  \App\Models\ProductOrder  $productOrder
     * @return void
     */
    public function created(ProductOrder $productOrder)
    {

        $product = Product::withTrashed()->find($productOrder->product_id);
        
        if($productOrder->quantity > 0 && $productOrder->type == 2){
            if($product->isProduct()){
                $product->decrement('stock_store', abs($productOrder->quantity));
            }
        }
    }

    /**
     * Handle the ProductOrder "updated" event.
     *
     * @param  \App\Models\ProductOrder  $productOrder
     * @return void
     */
    public function updated(ProductOrder $productOrder)
    {
        //
    }

    /**
     * Handle the ProductOrder "deleted" event.
     *
     * @param  \App\Models\ProductOrder  $productOrder
     * @return void
     */
    public function deleted(ProductOrder $productOrder)
    {
        $product = Product::withTrashed()->find($productOrder->product_id);
        
        if($productOrder->quantity > 0 && $productOrder->type == 2){
            if($product->isProduct()){
                $product->increment('stock_store', abs($productOrder->quantity));
            }
        }
    }

    /**
     * Handle the ProductOrder "restored" event.
     *
     * @param  \App\Models\ProductOrder  $productOrder
     * @return void
     */
    public function restored(ProductOrder $productOrder)
    {
        //
    }

    /**
     * Handle the ProductOrder "force deleted" event.
     *
     * @param  \App\Models\ProductOrder  $productOrder
     * @return void
     */
    public function forceDeleted(ProductOrder $productOrder)
    {
        //
    }
}

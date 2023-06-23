<?php

namespace App\Observers;

use App\Models\BatchProduct;

class BatchProductObserver
{
    /**
     * Handle the BatchProduct "created" event.
     *
     * @param  \App\Models\BatchProduct  $batchProduct
     * @return void
     */
    public function created(BatchProduct $batchProduct)
    {
       $batchProduct->update(['active' => $batchProduct->quantity]);
    }

    /**
     * Handle the BatchProduct "updated" event.
     *
     * @param  \App\Models\BatchProduct  $batchProduct
     * @return void
     */
    public function updated(BatchProduct $batchProduct)
    {
        //
    }

    /**
     * Handle the BatchProduct "deleted" event.
     *
     * @param  \App\Models\BatchProduct  $batchProduct
     * @return void
     */
    public function deleted(BatchProduct $batchProduct)
    {
        //
    }

    /**
     * Handle the BatchProduct "restored" event.
     *
     * @param  \App\Models\BatchProduct  $batchProduct
     * @return void
     */
    public function restored(BatchProduct $batchProduct)
    {
        //
    }

    /**
     * Handle the BatchProduct "force deleted" event.
     *
     * @param  \App\Models\BatchProduct  $batchProduct
     * @return void
     */
    public function forceDeleted(BatchProduct $batchProduct)
    {
        //
    }
}

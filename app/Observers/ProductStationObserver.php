<?php

namespace App\Observers;

use App\Models\ProductStation;
use Illuminate\Support\Facades\DB;

class ProductStationObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the ProductStation "created" event.
     *
     * @param  \App\Models\ProductStation  $productStation
     * @return void
     */
    public function created(ProductStation $productStation)
    {
        //
    }

    /**
     * Handle the ProductStation "updated" event.
     *
     * @param  \App\Models\ProductStation  $productStation
     * @return void
     */
    public function updated(ProductStation $productStation)
    {
        if($productStation->metadata['open'] <= 0 ){
            if($productStation->metadata['closed'] <= 0){
                DB::table('product_stations')->where('id', $productStation->id)->update(['active' => false]);
            }
        }
    }

    /**
     * Handle the ProductStation "deleted" event.
     *
     * @param  \App\Models\ProductStation  $productStation
     * @return void
     */
    public function deleted(ProductStation $productStation)
    {
        //
    }

    /**
     * Handle the ProductStation "restored" event.
     *
     * @param  \App\Models\ProductStation  $productStation
     * @return void
     */
    public function restored(ProductStation $productStation)
    {
        //
    }

    /**
     * Handle the ProductStation "force deleted" event.
     *
     * @param  \App\Models\ProductStation  $productStation
     * @return void
     */
    public function forceDeleted(ProductStation $productStation)
    {
        //
    }
}

<?php

namespace App\Observers;

use App\Models\MaterialOrder;
use App\Models\Material;

class MaterialOrderObserver
{
    /**
     * Handle the MaterialOrder "created" event.
     *
     * @param  \App\Models\MaterialOrder  $materialOrder
     * @return void
     */
    public function created(MaterialOrder $materialOrder)
    {
        $material = Material::find($materialOrder->material_id);
        
        if($materialOrder->quantity > 0){
            $material->decrement('stock', abs($materialOrder->quantity));
        }
    }

    /**
     * Handle the MaterialOrder "updated" event.
     *
     * @param  \App\Models\MaterialOrder  $materialOrder
     * @return void
     */
    public function updated(MaterialOrder $materialOrder)
    {
        //
    }

    /**
     * Handle the MaterialOrder "deleted" event.
     *
     * @param  \App\Models\MaterialOrder  $materialOrder
     * @return void
     */
    public function deleted(MaterialOrder $materialOrder)
    {
        $material = Material::find($materialOrder->material_id);
            
        if($materialOrder->quantity > 0){
            $material->increment('stock', abs($materialOrder->quantity));
        }
    }

    /**
     * Handle the MaterialOrder "restored" event.
     *
     * @param  \App\Models\MaterialOrder  $materialOrder
     * @return void
     */
    public function restored(MaterialOrder $materialOrder)
    {
        //
    }

    /**
     * Handle the MaterialOrder "force deleted" event.
     *
     * @param  \App\Models\MaterialOrder  $materialOrder
     * @return void
     */
    public function forceDeleted(MaterialOrder $materialOrder)
    {
        //
    }
}

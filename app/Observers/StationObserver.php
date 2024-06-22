<?php

namespace App\Observers;

use App\Models\Station;

class StationObserver
{
    /**
     * Handle the Station "created" event.
     *
     * @param  \App\Models\Station  $station
     * @return void
     */
    public function created(Station $station)
    {
        $station->update(['folio' => $station->last_folio_skip]);
    }

    /**
     * Handle the Station "updated" event.
     *
     * @param  \App\Models\Station  $station
     * @return void
     */
    public function updated(Station $station)
    {
        //
    }

    /**
     * Handle the Station "deleted" event.
     *
     * @param  \App\Models\Station  $station
     * @return void
     */
    public function deleted(Station $station)
    {
        //
    }

    /**
     * Handle the Station "restored" event.
     *
     * @param  \App\Models\Station  $station
     * @return void
     */
    public function restored(Station $station)
    {
        //
    }

    /**
     * Handle the Station "force deleted" event.
     *
     * @param  \App\Models\Station  $station
     * @return void
     */
    public function forceDeleted(Station $station)
    {
        //
    }
}

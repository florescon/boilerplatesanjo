<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Unit\UnitCreated;
use App\Events\Unit\UnitUpdated;
use App\Events\Unit\UnitDeleted;
use App\Events\Unit\UnitRestored;

class UnitEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('unit')
            ->performedOn($event->unit)
            ->withProperties([
                'unit' => [
                    'name' => $event->unit->name,
                    'abbreviation' => $event->unit->abbreviation,
                ],
            ])
            ->log(':causer.name creó unidad de medida :subject.name');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('unit')
            ->performedOn($event->unit)
            ->withProperties([
                'unit' => [
                    'name' => $event->unit->name,
                    'abbreviation' => $event->unit->abbreviation,
                ],
            ])
            ->log(':causer.name actualizó unidad de medida :subject.name');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('unit')
            ->performedOn($event->unit)
            ->log(':causer.name eliminó unidad de medida :subject.name');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        activity('unit')
            ->performedOn($event->unit)
            ->log(':causer.name restauró unidad de medida :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UnitCreated::class,
            'App\Listeners\UnitEventListener@onCreated'
        );

        $events->listen(
            UnitUpdated::class,
            'App\Listeners\UnitEventListener@onUpdated'
        );

        $events->listen(
            UnitDeleted::class,
            'App\Listeners\UnitEventListener@onDeleted'
        );

        $events->listen(
            UnitRestored::class,
            'App\Listeners\UnitEventListener@onRestored'
        );
    }
}

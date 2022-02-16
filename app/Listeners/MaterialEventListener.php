<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Material\MaterialCreated;
use App\Events\Material\MaterialUpdated;
use App\Events\Material\MaterialDeleted;
use App\Events\Material\MaterialRestored;

class MaterialEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('material')
            ->performedOn($event->material)
            ->withProperties([
                'material' => [
                    'name' => $event->material->name,
                    'part_number' => $event->material->part_number,
                    'description' => $event->material->description,
                    'acquisition_cost' => $event->material->acquisition_cost,
                    'price' => $event->material->price,
                    'stock' => $event->material->stock,
                ],
            ])
            ->log(':causer.name creó materia prima :subject.name');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('material')
            ->performedOn($event->material)
            ->withProperties([
                'material' => [
                    'name' => $event->material->name,
                    'part_number' => $event->material->part_number,
                    'description' => $event->material->description,
                    'acquisition_cost' => $event->material->acquisition_cost,
                    'price' => $event->material->price,
                ],
            ])
            ->log(':causer.name actualizó materia prima :subject.name');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('material')
            ->performedOn($event->material)
            ->log(':causer.name eliminó materia prima :subject.name');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        activity('material')
            ->performedOn($event->material)
            ->log(':causer.name restauró materia prima :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            MaterialCreated::class,
            'App\Listeners\MaterialEventListener@onCreated'
        );

        $events->listen(
            MaterialUpdated::class,
            'App\Listeners\MaterialEventListener@onUpdated'
        );

        $events->listen(
            MaterialDeleted::class,
            'App\Listeners\MaterialEventListener@onDeleted'
        );

        $events->listen(
            MaterialRestored::class,
            'App\Listeners\MaterialEventListener@onRestored'
        );
    }
}

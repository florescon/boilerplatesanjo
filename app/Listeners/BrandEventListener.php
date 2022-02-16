<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Brand\BrandCreated;
use App\Events\Brand\BrandUpdated;
use App\Events\Brand\BrandDeleted;
use App\Events\Brand\BrandRestored;

class BrandEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('brand')
            ->performedOn($event->brand)
            ->withProperties([
                'brand' => [
                    'name' => $event->brand->name,
                    'website' => $event->brand->website,
                    'description' => $event->brand->description,
                    'position' => $event->brand->position,
                ],
            ])
            ->log(':causer.name creó marca :subject.name');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('brand')
            ->performedOn($event->brand)
            ->withProperties([
                'brand' => [
                    'name' => $event->brand->name,
                    'website' => $event->brand->website,
                    'description' => $event->brand->description,
                    'position' => $event->brand->position,
                ],
            ])
            ->log(':causer.name actualizó marca :subject.name');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('brand')
            ->performedOn($event->brand)
            ->log(':causer.name eliminó marca :subject.name');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        activity('brand')
            ->performedOn($event->brand)
            ->log(':causer.name restauró marca :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            BrandCreated::class,
            'App\Listeners\BrandEventListener@onCreated'
        );

        $events->listen(
            BrandUpdated::class,
            'App\Listeners\BrandEventListener@onUpdated'
        );

        $events->listen(
            BrandDeleted::class,
            'App\Listeners\BrandEventListener@onDeleted'
        );

        $events->listen(
            BrandRestored::class,
            'App\Listeners\BrandEventListener@onRestored'
        );
    }
}

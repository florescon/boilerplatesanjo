<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Color\ColorCreated;
use App\Events\Color\ColorUpdated;
use App\Events\Color\ColorDeleted;
use App\Events\Color\ColorRestored;

class ColorEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('color')
            ->performedOn($event->color)
            ->withProperties([
                'color' => [
                    'name' => $event->color->name,
                    'short_name' => $event->color->short_name,
                    'color' => $event->color->color,
                    'secondary_color' => $event->color->secondary_color,
                ],
            ])
            ->log(':causer.name creó color :subject.name');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('color')
            ->performedOn($event->color)
            ->withProperties([
                'color' => [
                    'name' => $event->color->name,
                    'short_name' => $event->color->short_name,
                    'color' => $event->color->color,
                    'secondary_color' => $event->color->secondary_color,
                ],
            ])
            ->log(':causer.name actualizó color :subject.name');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('color')
            ->performedOn($event->color)
            ->log(':causer.name eliminó color :subject.name');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        activity('color')
            ->performedOn($event->color)
            ->log(':causer.name restauró color :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ColorCreated::class,
            'App\Listeners\ColorEventListener@onCreated'
        );

        $events->listen(
            ColorUpdated::class,
            'App\Listeners\ColorEventListener@onUpdated'
        );

        $events->listen(
            ColorDeleted::class,
            'App\Listeners\ColorEventListener@onDeleted'
        );

        $events->listen(
            ColorRestored::class,
            'App\Listeners\ColorEventListener@onRestored'
        );
    }
}

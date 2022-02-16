<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Line\LineCreated;
use App\Events\Line\LineUpdated;
use App\Events\Line\LineDeleted;
use App\Events\Line\LineRestored;

class LineEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('line')
            ->performedOn($event->line)
            ->withProperties([
                'line' => [
                    'name' => $event->line->name,
                ],
            ])
            ->log(':causer.name creó línea :subject.name');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('line')
            ->performedOn($event->line)
            ->withProperties([
                'line' => [
                    'name' => $event->line->name,
                ],
            ])
            ->log(':causer.name actualizó línea :subject.name');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('line')
            ->performedOn($event->line)
            ->log(':causer.name eliminó línea :subject.name');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        activity('line')
            ->performedOn($event->line)
            ->log(':causer.name restauró línea :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            LineCreated::class,
            'App\Listeners\LineEventListener@onCreated'
        );

        $events->listen(
            LineUpdated::class,
            'App\Listeners\LineEventListener@onUpdated'
        );

        $events->listen(
            LineDeleted::class,
            'App\Listeners\LineEventListener@onDeleted'
        );

        $events->listen(
            LineRestored::class,
            'App\Listeners\LineEventListener@onRestored'
        );
    }
}

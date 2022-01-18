<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Size\SizeCreated;
use App\Events\Size\SizeUpdated;
use App\Events\Size\SizeDeleted;
use App\Events\Size\SizeRestored;

class SizeEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('size')
            ->performedOn($event->size)
            ->withProperties([
                'size' => [
                    'name' => $event->size->name,
                    'short_name' => $event->size->short_name,
                ],
            ])
            ->log(':causer.name created size :subject.name');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('size')
            ->performedOn($event->size)
            ->withProperties([
                'size' => [
                    'name' => $event->size->name,
                    'short_name' => $event->size->short_name,
                ],
            ])
            ->log(':causer.name updated size :subject.name');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('size')
            ->performedOn($event->size)
            ->log(':causer.name deleted size :subject.name');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        activity('size')
            ->performedOn($event->size)
            ->log(':causer.name restored size :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            SizeCreated::class,
            'App\Listeners\SizeEventListener@onCreated'
        );

        $events->listen(
            SizeUpdated::class,
            'App\Listeners\SizeEventListener@onUpdated'
        );

        $events->listen(
            SizeDeleted::class,
            'App\Listeners\SizeEventListener@onDeleted'
        );

        $events->listen(
            SizeRestored::class,
            'App\Listeners\SizeEventListener@onRestored'
        );
    }
}

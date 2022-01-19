<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Service\ServiceCreated;
use App\Events\Service\ServiceUpdated;
use App\Events\Service\ServiceDeleted;
use App\Events\Service\ServiceRestored;

class ServiceEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('service')
            ->performedOn($event->service)
            ->withProperties([
                'service' => [
                    'name' => $event->service->name,
                    'code' => $event->service->code,
                    'price' => $event->service->price,
                ],
            ])
            ->log(':causer.name created service :subject.name');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('service')
            ->performedOn($event->service)
            ->withProperties([
                'service' => [
                    'name' => $event->service->name,
                    'code' => $event->service->code,
                    'price' => $event->service->price,
                ],
            ])
            ->log(':causer.name updated service :subject.name');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('service')
            ->performedOn($event->service)
            ->log(':causer.name deleted service :subject.name');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        activity('service')
            ->performedOn($event->service)
            ->log(':causer.name restored service :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ServiceCreated::class,
            'App\Listeners\ServiceEventListener@onCreated'
        );

        $events->listen(
            ServiceUpdated::class,
            'App\Listeners\ServiceEventListener@onUpdated'
        );

        $events->listen(
            ServiceDeleted::class,
            'App\Listeners\ServiceEventListener@onDeleted'
        );

        $events->listen(
            ServiceRestored::class,
            'App\Listeners\ServiceEventListener@onRestored'
        );
    }
}

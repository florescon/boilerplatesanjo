<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Cloth\ClothCreated;
use App\Events\Cloth\ClothUpdated;
use App\Events\Cloth\ClothDeleted;
use App\Events\Cloth\ClothRestored;

class ClothEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('cloth')
            ->performedOn($event->cloth)
            ->withProperties([
                'cloth' => [
                    'name' => $event->cloth->name,
                ],
            ])
            ->log(':causer.name creó tela :subject.name');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('cloth')
            ->performedOn($event->cloth)
            ->withProperties([
                'cloth' => [
                    'name' => $event->cloth->name,
                ],
            ])
            ->log(':causer.name actualizó tela :subject.name');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('cloth')
            ->performedOn($event->cloth)
            ->log(':causer.name eliminó tela :subject.name');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        activity('cloth')
            ->performedOn($event->cloth)
            ->log(':causer.name restauró tela :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ClothCreated::class,
            'App\Listeners\ClothEventListener@onCreated'
        );

        $events->listen(
            ClothUpdated::class,
            'App\Listeners\ClothEventListener@onUpdated'
        );

        $events->listen(
            ClothDeleted::class,
            'App\Listeners\ClothEventListener@onDeleted'
        );

        $events->listen(
            ClothRestored::class,
            'App\Listeners\ClothEventListener@onRestored'
        );
    }
}

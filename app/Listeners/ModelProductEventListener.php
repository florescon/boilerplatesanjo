<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ModelProduct\ModelProductCreated;
use App\Events\ModelProduct\ModelProductUpdated;
use App\Events\ModelProduct\ModelProductDeleted;
use App\Events\ModelProduct\ModelProductRestored;

class ModelProductEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('model_product')
            ->performedOn($event->model_product)
            ->withProperties([
                'model_product' => [
                    'name' => $event->model_product->name,
                ],
            ])
            ->log(':causer.name creó modelo del producto :subject.name');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('model_product')
            ->performedOn($event->model_product)
            ->withProperties([
                'model_product' => [
                    'name' => $event->model_product->name,
                ],
            ])
            ->log(':causer.name actualizó modelo del producto :subject.name');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('model_product')
            ->performedOn($event->model_product)
            ->log(':causer.name eliminó modelo del producto :subject.name');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        activity('model_product')
            ->performedOn($event->model_product)
            ->log(':causer.name restauró modelo del producto :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ModelProductCreated::class,
            'App\Listeners\ModelProductEventListener@onCreated'
        );

        $events->listen(
            ModelProductUpdated::class,
            'App\Listeners\ModelProductEventListener@onUpdated'
        );

        $events->listen(
            ModelProductDeleted::class,
            'App\Listeners\ModelProductEventListener@onDeleted'
        );

        $events->listen(
            ModelProductRestored::class,
            'App\Listeners\ModelProductEventListener@onRestored'
        );
    }
}

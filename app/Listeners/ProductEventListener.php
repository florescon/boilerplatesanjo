<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Product\ProductCreated;
use App\Events\Product\ProductUpdated;
use App\Events\Product\ProductDeleted;
use App\Events\Product\ProductRestored;
use App\Events\Product\ProductNameChanged;
use App\Events\Product\ProductCodeChanged;
use App\Events\Product\ProductDescriptionChanged;
use App\Events\Product\ProductLineChanged;
use App\Events\Product\ProductBrandChanged;
use App\Events\Product\ProductModelChanged;
use App\Events\Product\ProductColorCreated;

class ProductEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->withProperties([
                'product' => [
                    'name' => $event->product->name,
                    'code' => $event->product->code,
                    'description' => $event->product->description,
                    'price' => $event->product->price,
                ],
            ])
            ->log(':causer.name created product :subject.name');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->withProperties([
                'product' => [
                    'name' => $event->product->name,
                    'code' => $event->product->code,
                    'price' => $event->product->price,
                ],
            ])
            ->log(':causer.name updated product :subject.name');
    }

    /**
     * @param $event
     */
    public function onNameChanged($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->withProperties([
                'product' => [
                    'name' => $event->product->name,
                ],
            ])
            ->log(':causer.name changed product name :subject.name');
    }

    /**
     * @param $event
     */
    public function onCodeChanged($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->withProperties([
                'product' => [
                    'code' => $event->product->code,
                ],
            ])
            ->log(':causer.name changed product code :subject.name');
    }

    /**
     * @param $event
     */
    public function onDescriptionChanged($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->withProperties([
                'product' => [
                    'description' => $event->product->description,
                ],
            ])
            ->log(':causer.name changed product description :subject.name');
    }

    /**
     * @param $event
     */
    public function onLineChanged($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->withProperties([
                'product' => [
                    'line' => $event->product->line_id ? $event->product->line->name : 'None',
                ],
            ])
            ->log(':causer.name changed product line :subject.name');
    }

    /**
     * @param $event
     */
    public function onBrandChanged($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->withProperties([
                'product' => [
                    'brand' => $event->product->brand_id ? $event->product->brand->name : 'None',
                ],
            ])
            ->log(':causer.name changed product brand :subject.name');
    }

    /**
     * @param $event
     */
    public function onModelChanged($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->withProperties([
                'product' => [
                    'line' => $event->product->model_product_id ? $event->product->model_product->name : 'None',
                ],
            ])
            ->log(':causer.name changed product model :subject.name');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->log(':causer.name deleted product :subject.name');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->log(':causer.name restored product :subject.name');
    }

    /**
     * @param $event
     */
    public function onColorCreated($event)
    {
        activity('product')
            ->performedOn($event->product)
            ->withProperties([
                'product' => [
                    'name' => $event->product->name,
                    // 'color' => $event->product->color->name,
                ],
            ])
            ->log(':causer.name created color product :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ProductCreated::class,
            'App\Listeners\ProductEventListener@onCreated'
        );

        $events->listen(
            ProductUpdated::class,
            'App\Listeners\ProductEventListener@onUpdated'
        );

        $events->listen(
            ProductNameChanged::class,
            'App\Listeners\ProductEventListener@onNameChanged'
        );

        $events->listen(
            ProductCodeChanged::class,
            'App\Listeners\ProductEventListener@onCodeChanged'
        );

        $events->listen(
            ProductDescriptionChanged::class,
            'App\Listeners\ProductEventListener@onDescriptionChanged'
        );

        $events->listen(
            ProductLineChanged::class,
            'App\Listeners\ProductEventListener@onLineChanged'
        );

        $events->listen(
            ProductBrandChanged::class,
            'App\Listeners\ProductEventListener@onBrandChanged'
        );

        $events->listen(
            ProductModelChanged::class,
            'App\Listeners\ProductEventListener@onModelChanged'
        );

        $events->listen(
            ProductDeleted::class,
            'App\Listeners\ProductEventListener@onDeleted'
        );

        $events->listen(
            ProductRestored::class,
            'App\Listeners\ProductEventListener@onRestored'
        );

        $events->listen(
            ProductColorCreated::class,
            'App\Listeners\ProductEventListener@onColorCreated'
        );
    }
}
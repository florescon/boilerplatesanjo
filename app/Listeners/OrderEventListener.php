<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderUpdated;
use App\Events\Order\OrderDeleted;
use App\Events\Order\OrderAssignmentCreated;
use App\Events\Order\OrderAssignmentDeleted;
use App\Events\Order\OrderServiceCreated;
use App\Events\Order\OrderProductionStatusUpdated;
use App\Events\Order\OrderStatusUpdated;
use App\Events\Order\OrderPaymentCreated;

class OrderEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        activity('order')
            ->performedOn($event->order)
            ->withProperties([
                'order' => [
                    // 'id' => $event->id,
                    'parent_order' => $event->order->parent_order_id ?: null,
                    'user' => $event->order->user_id ? $event->order->user->name : null,
                    'departament' => $event->order->departament_id ? $event->order->departament->name : null,
                    'comment' => $event->order->name,
                    'internal_control' => !$event->order->user_id && !$event->order->departament_id ? 'internal control': null,  
                    'tracking_number' => $event->order->slug,
                    'date_entered' => $event->order->date_entered,
                    'from_store' => $event->order->from_store ? 'from store' : null,
                    'type' => $event->order->type_order_clear ?: null,
                ],
            ])
            ->log(':causer.name creó :properties.order.type #:subject.id número de seguimiento: :properties.order.tracking_number');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        activity('order')
            ->performedOn($event->order)
            ->withProperties([
                'order' => [
                    'comment' => $event->order->name,
                    'tracking_number' => $event->order->slug,
                ],
            ])
            ->log(':causer.name actualizó orden :subject.name');
    }

    /**
     * @param $event
     */
    public function onAssignmentCreated($event)
    {
        activity('order')
            ->performedOn($event->order)
            ->withProperties([
                'order' => [
                    'ticket' => $event->order->id,
                    'comment' => $event->order->name,
                    'tracking_number' => $event->order->slug,
                    'status' => $event->order->last_ticket->status->name ?? null,
                    'user' => $event->order->last_ticket->audi->name ?? null,
                ],
            ])
            ->log(':causer.name creó :properties.order.status ticket: #:subject.id, usuario: :properties.order.user');
    }

    /**
     * @param $event
     */
    public function onAssignmentDeleted($event)
    {
        activity('order')
            ->performedOn($event->order)
            ->withProperties([
                'order' => [
                    'ticket' => $event->order->id,
                    'comment' => $event->order->name,
                    'tracking_number' => $event->order->slug,
                    'status' => $event->order->last_ticket_updated->status->name ?? null,
                ],
            ])
            ->log(':causer.name eliminó :propertoes.order.status ticket :subject.id');
    }

    /**
     * @param $event
     */
    public function onServiceCreated($event)
    {
        activity('order')
            ->performedOn($event->order)
            ->withProperties([
                'order' => [
                    'tracking_number' => $event->order->slug,
                    'service' => $event->order->product_order->product->id
                ],
            ])
            ->log(':causer.name agregó servicio a la orden :subject.id');
    }

    /**
     * @param $event
     */
    public function onProductionStatusUpdated($event)
    {
        activity('order')
            ->performedOn($event->order)
            ->withProperties([
                'order' => [
                    'tracking_number' => $event->order->slug ?? null,
                    'order_production_status' => $event->order->last_status_order->status_id ? $event->order->last_status_order->status->name : null,
                ],
            ])
            ->log(':causer.name actualizó estado de producción, orden #:subject.id');
    }

    /**
     * @param $event
     */
    public function onStatusUpdated($event)
    {
        activity('order')
            ->performedOn($event->order)
            ->withProperties([
                'order' => [
                    'tracking_number' => $event->order->slug ?? null,
                    'order_delivery_status' => $event->order->last_order_delivery->formatted_type ?: null,
                ],
            ])
            ->log(':causer.name actualizó estado de entrega, orden #:subject.id');
    }

    /**
     * @param $event
     */
    public function onPaymentCreated($event)
    {
        activity('order')
            ->performedOn($event->order)
            ->withProperties([
                'order' => [
                    'tracking_number' => $event->order->slug ?? null,
                    'payment' => $event->order->last_payment->amount ?: null,
                    'type' => $event->order->type_order_clear ?: null,
                ],
            ])
            ->log(':causer.name creó pago :properties.order.type #:subject.id, cantidad $:properties.order.payment');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        activity('order')
            ->performedOn($event->order)
            ->withProperties([
                'order' => [
                    'comment' => $event->order->name,
                    'tracking_number' => $event->order->slug,
                    'type' => $event->order->type_order_clear ?: null,
                ],
            ])
            ->log(':causer.name eliminó :properties.order.type #:subject.id con número de seguimiento: :properties.order.tracking_number');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            OrderCreated::class,
            'App\Listeners\OrderEventListener@onCreated'
        );

        $events->listen(
            OrderUpdated::class,
            'App\Listeners\OrderEventListener@onUpdated'
        );

        $events->listen(
            OrderAssignmentCreated::class,
            'App\Listeners\OrderEventListener@onAssignmentCreated'
        );

        $events->listen(
            OrderAssignmentDeleted::class,
            'App\Listeners\OrderEventListener@onAssignmentDeleted'
        );

        $events->listen(
            OrderServiceCreated::class,
            'App\Listeners\OrderEventListener@onServiceCreated'
        );

        $events->listen(
            OrderProductionStatusUpdated::class,
            'App\Listeners\OrderEventListener@onProductionStatusUpdated'
        );

        $events->listen(
            OrderStatusUpdated::class,
            'App\Listeners\OrderEventListener@onStatusUpdated'
        );

        $events->listen(
            OrderPaymentCreated::class,
            'App\Listeners\OrderEventListener@onPaymentCreated'
        );

        $events->listen(
            OrderDeleted::class,
            'App\Listeners\OrderEventListener@onDeleted'
        );
    }
}

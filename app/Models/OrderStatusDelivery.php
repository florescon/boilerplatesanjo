<?php

namespace App\Models;

class OrderStatusDelivery
{
    /**
     * Pending orders.
     */
    public const PENDING = 'pending';

    /**
     * Orders that has been ready..
     */
    public const READY_FOR_DELIVERY = 'Entregado';

    /**
     * Orders that has been delivered..
     */
    public const DELIVERED = 'Entregado';


    public static function values(): array
    {
        return [
            self::PENDING => __('Pending'),
            self::READY_FOR_DELIVERY => __('Ready for delivery'),
            self::DELIVERED => __('Delivered'),
        ];
    }
}

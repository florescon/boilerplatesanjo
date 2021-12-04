<?php

namespace App\Models;

class OrderStatusPayment
{
    /**
     * Pending orders are brand new orders that have not been processed yet.
     */
    public const PENDING = 'pending';

    /**
     * Orders that has been registered..
     */
    public const ADVANCED = 'register';

    /**
     * Orders that has been paid..
     */
    public const PAID = 'paid';

    public static function values(): array
    {
        return [
            self::PENDING => __('Pending'),
            self::ADVANCED => __('Advanced'),
            self::PAID => __('Paid'),
        ];
    }

}

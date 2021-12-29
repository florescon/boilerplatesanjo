<?php

namespace App\Models;

class OrderStatusPayment
{
    /**
     * Pending orders are brand new orders that have not been processed yet.
     */
    public const PENDING = 'Pending';

    /**
     * Orders that has been registered..
     */
    public const ADVANCED = 'Advanced';

    /**
     * Orders that has been paid..
     */
    public const PAID = 'Paid';

    public static function values(): array
    {
        return [
            self::PENDING => __('Pending'),
            self::ADVANCED => __('Advanced'),
            self::PAID => __('Paid'),
        ];
    }

}

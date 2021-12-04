<?php

namespace App\Models;

class FinanceType
{
    /**
     * Finances that has been income..
     */
    const INCOME = 'income';

    /**
     * Finances that has been expense..
     */
    const EXPENSE = 'expense';

    public static function values(): array
    {
        return [
            self::INCOME   => __('Income'),
            self::EXPENSE    => __('Expense'),
        ];
    }
}

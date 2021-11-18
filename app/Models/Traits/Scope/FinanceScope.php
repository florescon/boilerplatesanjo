<?php

namespace App\Models\Traits\Scope;

/**
 * Class FinanceScope.
 */
trait FinanceScope
{
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyIncomes($query)
    {
        return $query->whereType('income');
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyExpenses($query)
    {
        return $query->whereType('expense');
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyNullCash($query)
    {
        return $query->whereNull('cash_id');
    }   
}

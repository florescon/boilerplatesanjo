<?php

namespace App\Models\Traits\Scope;

/**
 * Class OrderScope.
 */
trait OrderScope
{
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyOrders($query)
    {
        return $query->whereType(1);
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlySuborders($query)
    {
        return $query->whereNotNull('parent_order_id');
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlySales($query)
    {
        return $query->whereType(2);
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyMix($query)
    {
        return $query->whereType(3);
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyAll($query)
    {
        return $query;
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyFromStore($query)
    {
        return $query->where('from_store', true);
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyCashable($query)
    {
        return $query->where('from_store', true)->whereNull('cash_id');
    }
}

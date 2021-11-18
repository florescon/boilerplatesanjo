<?php

namespace App\Models\Traits\Scope;
use Carbon\Carbon;

/**
 * Class DateScope.
 */
trait DateScope
{

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeCurrentWeek($query)
    {
        return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeToday($query)
    {
        return $query->where('created_at', '>=', Carbon::today());
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeYesterday($query)
    {
        return $query->whereBetween('created_at', [Carbon::yesterday(), Carbon::today()]);
    }   

}

<?php

namespace App\Models\Traits\Scope;

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
        $now = Carbon::now();
        return $query->whereBetween('updated_at', [$now->startOfMonth(), $now]);
    }   

}

<?php

namespace App\Models\Frontend\Traits\Scope;

/**
 * Class ProductScope.
 */
trait ProductScope
{

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNewness($query)
    {
        return $query->orderBy('created_at', 'desc');
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopePriceDesc($query)
    {
        return $query->orderBy('price', 'desc');
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopePriceAsc($query)
    {
        return $query->orderBy('price', 'asc');
    }   


    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeDefaultOrder($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }   

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyActive($query)
    {
        return $query->whereStatus(true);
    }


}

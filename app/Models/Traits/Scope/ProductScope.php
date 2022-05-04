<?php

namespace App\Models\Traits\Scope;

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
    public function scopeOnlyActive($query)
    {
        return $query->whereStatus(true);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyServices($query)
    {
        return $query->whereType(false);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyProducts($query)
    {
        return $query->whereType(true);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlySubProducts($query)
    {
        return $query->whereType(true)->whereNotNull('parent_id');
    }
}

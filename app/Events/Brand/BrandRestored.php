<?php

namespace App\Events\Brand;

use Illuminate\Queue\SerializesModels;
use App\Models\Brand;

/**
 * Class BrandRestored.
 */
class BrandRestored
{
    use SerializesModels;

    /**
     * @var
     */
    public $brand;

    /**
     * @param $brand
     */
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
}

<?php

namespace App\Events\Brand;

use Illuminate\Queue\SerializesModels;
use App\Models\Brand;

/**
 * Class BrandCreated.
 */
class BrandCreated
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

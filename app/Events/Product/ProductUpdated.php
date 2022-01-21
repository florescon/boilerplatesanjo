<?php

namespace App\Events\Product;

use Illuminate\Queue\SerializesModels;
use App\Models\Product;

/**
 * Class ProductUpdated.
 */
class ProductUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $product;

    /**
     * @param $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}

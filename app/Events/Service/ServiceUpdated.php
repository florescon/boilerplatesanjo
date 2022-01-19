<?php

namespace App\Events\Service;

use Illuminate\Queue\SerializesModels;
use App\Models\Product;

/**
 * Class ServiceUpdated.
 */
class ServiceUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $service;

    /**
     * @param $service
     */
    public function __construct(Product $service)
    {
        $this->service = $service;
    }
}

<?php

namespace App\Events\Cloth;

use Illuminate\Queue\SerializesModels;
use App\Models\Cloth;

/**
 * Class ClothCreated.
 */
class ClothCreated
{
    use SerializesModels;

    /**
     * @var
     */
    public $cloth;

    /**
     * @param $cloth
     */
    public function __construct(Cloth $cloth)
    {
        $this->cloth = $cloth;
    }
}

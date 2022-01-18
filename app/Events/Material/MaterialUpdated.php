<?php

namespace App\Events\Material;

use Illuminate\Queue\SerializesModels;
use App\Models\Material;

/**
 * Class MaterialUpdated.
 */
class MaterialUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $material;

    /**
     * @param $material
     */
    public function __construct(Material $material)
    {
        $this->material = $material;
    }
}

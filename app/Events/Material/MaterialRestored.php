<?php

namespace App\Events\Material;

use Illuminate\Queue\SerializesModels;
use App\Models\Material;

/**
 * Class MaterialRestored.
 */
class MaterialRestored
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

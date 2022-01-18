<?php

namespace App\Events\Color;

use Illuminate\Queue\SerializesModels;
use App\Models\Color;

/**
 * Class ColorUpdated.
 */
class ColorUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $color;

    /**
     * @param $color
     */
    public function __construct(Color $color)
    {
        $this->color = $color;
    }
}

<?php

namespace App\Events\Line;

use Illuminate\Queue\SerializesModels;
use App\Models\Line;

/**
 * Class LineUpdated.
 */
class LineUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $line;

    /**
     * @param $line
     */
    public function __construct(Line $line)
    {
        $this->line = $line;
    }
}

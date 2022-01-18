<?php

namespace App\Events\Line;

use Illuminate\Queue\SerializesModels;
use App\Models\Line;

/**
 * Class LineDeleted.
 */
class LineDeleted
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

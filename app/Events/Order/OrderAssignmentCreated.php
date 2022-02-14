<?php

namespace App\Events\Order;

use Illuminate\Queue\SerializesModels;
use App\Models\Order;

/**
 * Class OrderAssignmentCreated.
 */
class OrderAssignmentCreated
{
    use SerializesModels;

    /**
     * @var
     */
    public $order;

    /**
     * @param $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}

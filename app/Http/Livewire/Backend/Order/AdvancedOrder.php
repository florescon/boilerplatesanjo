<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;

class AdvancedOrder extends Component
{
    public $order_id, $lates_statusId, $slug, $isComment, $comment, $isDate, $date_entered;

    protected $listeners = ['reasignUserStore' => 'render', 'reasignDepartamentStore' => 'render'];

    public function mount(Order $order)
    {
        $this->order_id = $order->id;
    }

    public function render()
    {
        $order = Order::with('suborders')->findOrFail($this->order_id);

        $limit = $order->created_at->addDays(7);
        $now = Carbon::now();
        $result = $now->gt($limit);

        return view('backend.order.livewire.advanced-order')->with(compact('order', 'result'));
    }
}

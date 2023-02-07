<?php

namespace App\Http\Livewire\Backend\ServiceOrder;

use Livewire\Component;
use App\Models\Order;

class ServiceOrderTable extends Component
{
    public $order;

    protected $listeners = ['ServiceOrderCreated' => 'render'];

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->order_id = $order->id;
    }

    public function render()
    {
        $service_orders = $this->order->service_orders()->orderBy('created_at', 'desc')->paginate('10');

        return view('backend.serviceorder.service-order-table', [
            'service_orders' => $service_orders,
        ]);
    }
}

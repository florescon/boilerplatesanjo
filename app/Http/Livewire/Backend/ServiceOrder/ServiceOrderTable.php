<?php

namespace App\Http\Livewire\Backend\ServiceOrder;

use Livewire\Component;
use App\Models\ServiceOrder;
use App\Models\Order;

class ServiceOrderTable extends Component
{
    public $order;

    protected $listeners = ['ServiceOrderCreated' => 'render', 'triggerRefresh' => '$refresh'];

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->order_id = $order->id;
    }

    public function delete(int $id)
    {
        if($id)
            $serviceOrder = ServiceOrder::where('id', $id)->first();
            $serviceOrder->delete();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        $service_orders = $this->order->service_orders()->orderBy('created_at', 'desc')->paginate('10');

        return view('backend.serviceorder.service-order-table', [
            'service_orders' => $service_orders,
        ]);
    }
}

<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Status;
use App\Models\StatusOrder;
use Illuminate\Support\Facades\Auth;

class EditOrder extends Component
{

    public $order_id, $lates_statusId;


    protected $listeners = ['updateStatus' => '$refresh'];

    public function mount(Order $order)
    {
        $this->order_id = $order->id;
        $this->lates_statusId = $order->load('last_status_order')->last_status_order->status_id ?? null;
        $this->initstatus($order);

    }

    public function updateStatus($statusId): void
    {


        if($statusId != $this->lates_statusId){
            StatusOrder::create([
                'order_id' => $this->order_id,
                'status_id' => $statusId,
                'audi_id' => Auth::id(),
            ]);
        }

        $order = Order::findOrFail($this->order_id);
        $this->initstatus($order); // re-initialize the component state with fresh data after saving

        sleep(3);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Status changed'), 
        ]);

    }

    private function initstatus(Order $order)
    {
        $this->lates_statusId = $order->load('last_status_order')->last_status_order->status_id ?? null;
    }


    public function render()
    {

        $model = Order::with('product_order', 'suborders.user', 'last_status_order')->findOrFail($this->order_id);

        $statuses = Status::orderBy('level')->get();

        return view('backend.order.livewire.edit')->with(compact('model', 'statuses'));
    }
}

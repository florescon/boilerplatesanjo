<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Status;
use App\Models\StatusOrder;
use App\Models\Assignment;
// use Illuminate\Database\Eloquent\Builder;

class WhereIsProducts extends Component
{

    public $order_id, $lates_statusId;


    public function mount(Order $order)
    {
        $this->order_id = $order->id;
        $this->lates_statusId = $order->load('last_status_order')->last_status_order->status_id ?? null;

    }

    public function outputUpdate($assignmentID)
    {
        $assignmentUpd = Assignment::find($assignmentID);
        $assignmentUpd->update([
            'output' => true,
        ]);

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
    }




    public function render()
    {

        $model = Order::with(['tickets.status', 'tickets.assignments_direct.assignmentable.product', 'tickets.user', 'tickets' =>function($q) {
            $q->whereHas('assignments_direct', function($query) {
                $query->where('output', false);
            });            
        }])->findOrFail($this->order_id);


        return view('backend.order.livewire.where-is-products')->with(compact('model'));
    }

}

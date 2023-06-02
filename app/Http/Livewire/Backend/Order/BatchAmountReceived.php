<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\BatchProduct;
use Illuminate\Support\Facades\Auth;

class BatchAmountReceived extends Component
{
    public $batch_id;
    public $received;

    protected $listeners = [
        'forceRenderAssignmentAmount' => 'render'
    ];

    protected $rules = [
        'received' => 'required|integer|min:1',
    ];

    public function mount(BatchProduct $batch)
    {
        $this->batch_id = $batch->id;
    }

    public function receivedAmount($batchID)
    {
        $this->validate();

        $batchUpddate = BatchProduct::find($batchID);

        if($this->received > $batchUpddate->difference){
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title'   => __('Check the quantity'), 
            ]);
        }
        else{
            $batchUpddate->received()->create([
                'batch_product_id' => $batchUpddate->id,
                'product_id' => $batchUpddate->product_id,
                'quantity' => $this->received,
                'approved' => now(),
                'approved_by' => Auth::id(),
            ]);

            $this->initreceived($batchUpddate);

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Saved'), 
            ]);
        }

        $this->emit('AmountReceived');
    }

    private function initreceived(BatchProduct $bathProduct)
    {
        $this->clearReceived();
    }

    public function clearReceived()
    {
        $this->received = '';
    }

    public function render()
    {
        $batch = BatchProduct::findOrFail($this->batch_id);

        return view('backend.order.livewire.batch-amount-received')->with(compact('batch'));
    }
}

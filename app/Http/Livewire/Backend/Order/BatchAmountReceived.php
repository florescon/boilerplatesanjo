<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\BatchProduct;
use App\Models\Batch;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use DB;

class BatchAmountReceived extends Component
{
    public $batch_id;
    public $received;

    public $last_status;
    public $batch_id_parent;
    public $order_id;

    protected $listeners = [
        'forceRenderAssignmentAmount' => 'render'
    ];

    protected $rules = [
        'received' => 'required|integer|min:1',
    ];

    public function mount(BatchProduct $batch, ?int $last_status = null)
    {
        $this->batch_id = $batch->id;
        $this->batch_id_parent = $batch->batch_id;
        $this->order_id = $batch->order_id;
        $this->last_status = $last_status;
    }

    public function receivedAmount($batchID)
    {
        $this->validate();

        $batchUpdate = BatchProduct::find($batchID);

        if($this->received > $batchUpdate->difference){
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title'   => __('Check the quantity'), 
            ]);
        }
        else{

            $firstProcess = \App\Models\Status::firstStatusProcess();


            if(!$this->last_status && $firstProcess){

                $batchUpdate->decrement('active', $this->received);

                $batchToProcess = Batch::firstOrCreate(
                    ['order_id' => $this->order_id, 'status_id' => $firstProcess->id, 'batch_id' => $this->batch_id_parent],
                );

                $batchToProcess->batch_product()->updateOrCreate(
                    [
                        'order_id' =>  $this->order_id,
                        'batch_id' =>  $batchToProcess->id,
                        'batch_product_id' => $batchUpdate->id,
                        'product_order_id' => $batchUpdate->product_order_id,
                        'product_id' =>  $batchUpdate->product_id,
                        'status_id' =>  $firstProcess->id,
                        'personal_id' =>  null,
                    ]
                )->increment('quantity', $this->received, ['active' => DB::raw("IF(ISNULL(active),$this->received,$this->received + active)")]); 
            }

            $batchUpdate->received()->create([
                'batch_product_id' => $batchUpdate->id,
                'product_id' => $batchUpdate->product_id,
                'quantity' => $this->received,
                'approved' => now(),
                'approved_by' => Auth::id(),
            ]);

            $this->initreceived($batchUpdate);

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

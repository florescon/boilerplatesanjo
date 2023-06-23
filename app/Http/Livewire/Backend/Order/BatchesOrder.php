<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Batch;
use App\Models\Status;
use App\Models\ProductOrder;
use App\Models\BatchProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Carbon\Carbon;
use Exception;
use App\Events\Order\OrderAssignmentCreated;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class BatchesOrder extends Component
{
    public $order_id, $status_id, $quantity, $user, $status_name;

    public $q;

    public $sumQuantity;

    public $next_status, $previous_status;

    public ?string $date = null;
    public ?string $date_entered = null;

    public $output;

    protected $listeners = ['selectedCompanyItem', 'save' => '$refresh', 'AmountReceived' => 'render'];

    public function mount(Order $order, Status $status)
    {
        $this->order_id = $order->id;
        $this->status_id = $status->id;
        $this->next_status = Status::where('level', '>', $status->level)->where('to_add_users', true)->where('batch', true)
                ->oldest('level')
                ->first();
        $this->previous_status = Status::where('level', '<', $status->level)->where('to_add_users', true)->where('batch', true)
                ->latest('level')
                ->first();
        $this->status_name = $status->name;
    }

    protected $rules = [
        'user' => 'required',
    ];

    public function selectedCompanyItem($item)
    {
        if ($item)
            $this->user = $item;
        else
            $this->user = null;
    }

    public function outputUpdateAll($batchID)
    {
        $batchUp = Batch::find($batchID);

        $firstProcess = \App\Models\Status::firstStatusProcess();

        $products = $batchUp->batch_product()->get();
        
        foreach($products as $product){

            if($product->difference > 0){

                // dd($product->active + $product->difference);

                if(!$this->next_status && $firstProcess){
                    
                    $batchToProcess = Batch::firstOrCreate(
                        ['order_id' => $this->order_id, 'status_id' => $firstProcess->id, 'batch_id' => $product->batch_id],
                    );

                    $si = $batchToProcess->batch_product()->updateOrCreate(
                        [
                            'order_id' =>  $this->order_id,
                            'batch_id' =>  $batchToProcess->id,
                            'batch_product_id' => $product->id,
                            'product_order_id' => $product->product_order_id,
                            'product_id' =>  $product->product_id,
                            'status_id' =>  $firstProcess->id,
                            'personal_id' =>  null,
                        ]
                    )->increment('quantity', $product->difference, ['active' => DB::raw("IF(ISNULL(active), $product->difference, $product->difference + active)") ]); 

                    $product->decrement('active', $product->difference);
                }

                $product->received()->create([
                    'batch_product_id' => $product->id,
                    'product_id' => $product->product_id,
                    'quantity' => $product->difference,
                    'approved' => now(),
                    'approved_by' => Auth::id(),
                ]);
            }

        }

        $this->emit('forceRenderAssignmentAmount');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function saveDate($ticketID)
    {
        $this->validate([
            'date_entered' => 'required|max:100',
        ]);

        $ticket = Batch::findOrFail($ticketID);
        $newDate = (string)Str::of($this->date_entered)->trim()->substr(0, 100); // trim whitespace & more than 100 characters

        $ticket->date_entered = $newDate ?? null;
        $ticket->save();

        $this->date_entered = null;

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
    }

    public function updatedQuantity()
    {
        $s = 0;
        foreach($this->quantity as $q){
            if($q['available'] > 0){
                $s += $q['available'];
            }
        }

        $this->sumQuantity = $s;
    }

    public function save()
    {
        $this->validate();

        $orderModel = Order::with('products')->find($this->order_id);

        foreach($orderModel->products as $bal)
        {
            if(is_array($this->quantity) && array_key_exists($bal->id, $this->quantity)){
                $this->validate([
                    'quantity.'.$bal->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$bal->available_batch,
                ]);
            }
        }

        DB::beginTransaction();

        try {

            if(!empty($this->quantity)){

                $batch = new Batch([
                    'status_id' => $this->status_id,
                    'personal_id' => $this->user ?? null,
                    'date_entered' => $this->date ?: today(),
                    'audi_id' => Auth::id(),
                ]);

                $orderModel->batches()->save($batch);                

                foreach($this->quantity as $key => $product){
                    if(!empty($product['available'])){

                        $product_order = ProductOrder::where('id', $key)->withTrashed()->first();

                        $batch->batch_product()->create([
                            'order_id' =>  $this->order_id,
                            'ticket_id' =>  $batch->id,
                            'product_order_id' => $key,
                            'product_id' =>  $product_order->product_id,
                            'product_parent_id' => $product_order->product->parent_or_id,
                            'status_id' =>  $this->status_id,
                            'personal_id' =>  $this->user ?? null,
                            'quantity' => $product['available'],
                        ]); 
                    }
                }

                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => __('Saved'), 
                ]);

            }

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem.'));
        }

        DB::commit();

       $this->resetInput();
    }

    public function continue(?int $batch_id = null)
    {
        // dd($batch_id);

        $this->validate();

        $batch = Batch::with('batch_product.product')->find($batch_id);

        foreach($batch->batch_product as $bal)
        {
            if(is_array($this->q) && array_key_exists($bal->id, $this->q)){
                $this->validate([
                    'q.'.$bal->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$bal->available,
                ]);
            }
        }

        DB::beginTransaction();

        try {

            if(!empty($this->q)){

                $batchCreate = new Batch([
                    'order_id' => $this->order_id,
                    'status_id' => $this->status_id,
                    'personal_id' => $this->user ?? null,
                    'date_entered' => $this->date ?: today(),
                    'batch_id' => $batch_id,
                    'batch_parent_id' => $batch->batch_parent_id ?? $batch_id,
                    'audi_id' => Auth::id(),
                ]);

                $batch->children()->save($batchCreate);                

                foreach($this->q as $key => $product){

                    if(!empty($product['available'])){

                        $batch_product = BatchProduct::where('id', $key)->withTrashed()->first();

                        $batch_product->decrement('active', $product['available']);

                        $batchCreate->batch_product()->create([
                            'order_id' =>  $this->order_id,
                            'batch_id' =>  $batchCreate->id,
                            'batch_product_id' => $key,
                            'product_order_id' => $batch_product->product_order_id,
                            'product_id' =>  $batch_product->product_id,
                            'product_parent_id' => $batch_product->product->parent_or_id,
                            'status_id' =>  $this->status_id,
                            'personal_id' =>  $this->user ?? null,
                            'quantity' => $product['available'],
                        ]); 
                    }
                }

                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => __('Saved'), 
                ]);

            }

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem.'));
        }

        DB::commit();

       $this->resetInput();
    }

    public function resetInput()
    {
        $this->quantity = '';
        $this->q = '';
        $this->sumQuantity = '';
    }

    public function removeProduct($productId): void
    {
        $delete = BatchProduct::where('id', $productId)->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        $statusId = $this->status_id;

        $firstBatch = \App\Models\Status::firstStatusBatch();
        $lastBatch = \App\Models\Status::lastStatusBatch();

        if(!$this->previous_status){
            $model = Order::with(['products'
                        ])->findOrFail($this->order_id);
        }
        else{
            $model = Order::with([
                'batches' => [
                    'status',
                    'personal',
                    'batch_product' => [
                        'received',
                        'product' => [
                            'color',
                            'parent',
                            'size',
                        ],
                    ],

                ],
                'batches' => function($query) use ($statusId){
                                $query->whereIn('status_id', [$statusId, $this->previous_status['id'] ?? null]);
                            },
                    ])->findOrFail($this->order_id);
        }

        return view('backend.order.livewire.batches')->with([
            'model' => $model,
            'firstBatch' => $firstBatch,
            'lastBatch' => $lastBatch,
        ]);
    }
}

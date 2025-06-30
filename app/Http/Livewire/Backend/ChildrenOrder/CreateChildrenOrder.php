<?php

namespace App\Http\Livewire\Backend\ChildrenOrder;

use Livewire\Component;
use App\Models\Order;
use App\Models\ProductOrder;
use App\Models\ServiceOrder;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class CreateChildrenOrder extends Component
{
    public $order;

    public $comment_general, $file_text, $dimensions;

    public $image_id, $service_type_id, $quantity, $comment;

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->order_id = $order->id;
    }

    protected $rules = [
        'image_id' => 'required',
        'service_type_id' => 'required',
    ];

    protected $validationAttributes = [
        'image_id' => 'image',
        'service_type_id' => 'service',
    ];

    public function save()
    {

        foreach($this->order->products as $bal)
        {
            if(is_array($this->quantity) && array_key_exists($bal->id, $this->quantity)){

                $this->validate([
                    'quantity.'.$bal->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$bal->quantity,
                ]);
            }
        }

        DB::beginTransaction();

        try {

            if(!empty($this->quantity)){


                $serviceOrder = Order::create([
                    'parent_order_id' => $this->order_id,
                    'user_id' => $this->order->user_id ?? null,
                    'comment' => $this->order->comment ?? null,
                    'audi_id' => Auth::id(),
                    'approved' => true,
                    'type' => 1,
                    'branch_id' => false,
                    'from_quotation' => false,
                    'flowchart' => true,
                ]);

                foreach($this->quantity as $key => $quantity){
                    $productOrder = ProductOrder::where('id', $key)->first();

                    if($quantity > 0){
                        $serviceOrder->product_order()->create([
                            'quantity' => $quantity['available'],
                            'product_id' => $productOrder->product_id,
                            'price' => $productOrder->price,
                            'price_without_tax' => $productOrder->price_without_tax,
                            'type' => $productOrder->type,
                            'comment' => $productOrder->comment,
                            'product_id' => $productOrder->product_id,
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

                $this->emit('swal:alert', [
                    'icon' => 'error',
                    'title'   => __('Error'), 
                ]);

            throw new GeneralException(__('There was a problem.'));
        }

        DB::commit();

       $this->resetInput();

       $this->emit('ServiceOrderCreated');
    }

    public function resetInput()
    {
        $this->dimensions = '';
        $this->quantity = '';
        $this->comment = '';
    }

    public function render()
    {
        $products = $this->order->products()->orderBy('created_at', 'desc')->get();

        return view('backend.childrenorder.create-children-order', [
            'products' => $products,
        ]);
    }
}

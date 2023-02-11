<?php

namespace App\Http\Livewire\Backend\ServiceOrder;

use Livewire\Component;
use App\Models\Order;
use App\Models\ServiceOrder;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class CreateServiceOrder extends Component
{
    public $order;

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
        $this->validate();

        foreach($this->order->products as $bal)
        {
            if(is_array($this->quantity) && array_key_exists($bal->id, $this->quantity)){

                $this->validate([
                    'quantity.'.$bal->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$bal->quantity,
                    'comment.'.$bal->id.'.available' => 'sometimes|nullable',
                ]);
            }
        }

        DB::beginTransaction();

        try {

            if(!empty($this->quantity)){

                $serviceOrder = ServiceOrder::create([
                    'order_id' => $this->order_id,
                    'service_type_id' => $this->service_type_id ?? null,
                    'image_id' => $this->image_id ?? null,
                    'created_id' => Auth::id(),
                    'branch_id' => $this->order->branch_id ?? 0,
                ]);

                foreach($this->quantity as $key => $quantity){
                    if($quantity > 0){
                        $serviceOrder->product_service_orders()->create([
                            'quantity' => $quantity['available'],
                            'comment' => $this->comment[$key] ?? '',
                            'product_id' => $key,
                        ]);        
                    }
                }

                $this->emit('clear-image');
                $this->emit('clear-service-type');

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
        $this->quantity = '';
        $this->comment = '';
    }

    public function render()
    {
        $products = $this->order->products()->orderBy('created_at', 'desc')->get();

        return view('backend.serviceorder.create-service-order', [
            'products' => $products,
        ]);
    }
}

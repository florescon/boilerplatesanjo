<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Departament;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Events\Order\OrderCreated;

class Suborders extends Component
{
    public $order_id, $quantityy, $departament, $status_name;

    protected $listeners = ['selectedDeparament', 'savesuborder' => '$refresh'];

    public function mount(Order $order)
    {
        $this->order_id = $order->id;
    }

    protected $rules = [
        'departament' => 'required',
    ];

    public function selectedDeparament($item)
    {
        if ($item)
            $this->departament = $item;
        else
            $this->departament = null;
    }

    public function savesuborder()
    {
        $this->validate();

        $orderModel = Order::with('product_order')->find($this->order_id);

        foreach($orderModel->product_order as $bal)
        {

            if(is_array($this->quantityy) && array_key_exists($bal->id, $this->quantityy)){

                $available = $bal->quantity - $orderModel->getTotalAvailableByProduct($bal->id);

                $this->validate([
                    'quantityy.'.$bal->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$available,
                ]);
            }
        }

        if(!empty($this->quantityy)){

            // dd($this->quantityy);
            $suborder = new Order();
            $suborder->parent_order_id = $this->order_id;
            $suborder->departament_id = $this->departament ?? null;
            $suborder->date_entered = Carbon::now()->format('Y-m-d');
            $suborder->audi_id = Auth::id();
            $suborder->approved = true;
            $suborder->type = 4;
            $suborder->save();

            event(new OrderCreated($suborder));

            $departament = Departament::find($this->departament);

            foreach($this->quantityy as $key => $product){

                // dd($product['available']);
                if(!empty($product['available'])){

                    $SuborderIntoPro = $suborder;

                    $getProductOrder = ProductOrder::find($key)->product_id;

                    $getProduct = Product::with('parent')->withTrashed()->find($getProductOrder);

                    $SuborderIntoPro->product_suborder()->create([
                        'product_id' => $getProductOrder,
                        'quantity' => $product['available'],
                        'price' => $this->departament ? $getProduct->getPrice($departament->type_price ?? 'retail') : null,
                        'parent_product_id' => $key,
                    ]);
                }
            }
        }

       $this->resetInput();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
    }

    public function resetInput()
    {
        $this->quantityy = '';
    }

    public function render()
    {
        $model = Order::with('suborders.user', 'product_order.product')->findOrFail($this->order_id);

        return view('backend.order.livewire.suborders')->with(compact('model'));
    }
}

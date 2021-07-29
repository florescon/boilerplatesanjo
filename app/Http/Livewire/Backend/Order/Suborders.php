<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class Suborders extends Component
{

    public $order_id, $quantityy, $user, $status_name;

    protected $listeners = ['selectedCompanyItem', 'savesuborder' => '$refresh'];

    public function mount(Order $order)
    {
        $this->order_id = $order->id;
    }

    public function selectedCompanyItem($item)
    {
        if ($item)
            $this->user = $item;
        else
            $this->user = null;
    }

    public function savesuborder()
    {


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

        DB::beginTransaction();

        try {

            if(!empty($this->quantityy)){

                // dd($this->quantityy);
                $suborder = new Order();
                $suborder->parent_order_id = $this->order_id;
                $suborder->user_id = $this->user ?? null;
                $suborder->date_entered = Carbon::now()->format('Y-m-d');
                $suborder->audi_id = Auth::id();
                $suborder->save();

                foreach($this->quantityy as $key => $product){

                    // dd($product['available']);
                    if(!empty($product['available'])){

                        $SuborderIntoPro = $suborder;

                        $SuborderIntoPro->product_suborder()->create([
                            'product_id' => $key,
                            'quantity' => $product['available'],
                            'price' => null,
                        ]);
                    }
                }
            }

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem.'));
        }

        DB::commit();

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

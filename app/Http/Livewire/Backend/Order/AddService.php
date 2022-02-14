<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Events\Order\OrderServiceCreated;

class AddService extends Component
{
    public ?int $amount = 1;
    public ?string $price = null;
    public ?int $service = null;

    public $orderId;

    public ?int $parameter = 1;

    protected $listeners = ['selectedService', 'createmodal'];

    public function createmodal(int $id, ?int $parameterr = 1)
    {
        $this->parameter = $parameterr;
        $this->orderId = $id;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->amount = 1;
        $this->service = null;
        $this->price = null;
    }

    public function selectedService($service)
    {
        // dd($this->parameter);
        if ($service){
            $this->service = $service;
            $getService = Product::findOrFail($service);
            $this->price = $getService->price;
        }
        else{
            $this->service = null;
        }
    }

    public function store()
    {
        $order = Order::find($this->orderId);

        $this->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $order->product_order()->create([
            'product_id' => $this->service,
            'quantity' => $this->amount,
            'price' =>  $this->price,
            'type' => $this->parameter,
        ]);

        // event(new OrderServiceCreated($order));

        $this->resetInputFields();
        $this->emit('serviceStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);
 
         return $this->redirectRoute('admin.order.edit', $this->orderId);
   }

    public function render()
    {
        return view('backend.order.livewire.add-service');
    }
}
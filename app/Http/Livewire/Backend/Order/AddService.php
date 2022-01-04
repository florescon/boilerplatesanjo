<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;

class AddService extends Component
{
    public ?int $amount = 1;
    public ?string $price = null;
    public ?int $service = null;

    public $orderId;

    protected $listeners = ['selectedService', 'createmodal'];

    public function createmodal(int $id)
    {
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
            'type' => 1,
        ]);

        $this->resetInputFields();
        $this->emit('serviceStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);
    }

    public function render()
    {
        return view('backend.order.livewire.add-service');
    }
}
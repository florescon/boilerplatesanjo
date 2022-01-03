<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;

class CreateService extends Component
{
    public ?string $amount = null;
    public ?int $service = null;

    public $orderId;

    protected $listeners = ['selectService', 'createmodal'];

    public function createmodal(int $id)
    {
        $this->orderId = $id;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->amount = '';
    }

    public function selectService($service)
    {
        if ($service)
            $this->service = $service;
        else
            $this->service = null;
    }

    public function store()
    {
        $order = Order::find($this->orderId);
        $serviceSelected = Product::find($this->service);

        $this->validate([
            'amount' => 'required|numeric|min:0.01|regex:/^\d*(\.\d{1,2})?$/|max:'.$max->total_payments_remaining,
            'comment' => 'sometimes',
            'payment_method' => 'required_with:amount',
        ]);

        $order->product_order()->create([
            'product_id' => $this->service,
            'quantity' => $this->amount,
            'price' =>  !is_null($this->price) || $this->price != 0 ? 
                            $this->price : $serviceSelected->price,
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
        return view('backend.order.livewire.create-service');
    }
}

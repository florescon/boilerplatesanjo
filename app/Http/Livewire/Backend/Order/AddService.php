<?php

namespace App\Http\Livewire\Backend\Order;

use App\Domains\Auth\Models\User;
use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Events\Order\OrderServiceCreated;

class AddService extends Component
{
    public ?int $amount = 1;
    public ?string $price = null;
    public ?string $priceWithoutTax = null;
    public ?int $service = null;

    public $orderId;

    public ?int $parameter = 1;
    public $fromStore;

    protected $listeners = ['selectedService', 'createmodal'];

    public function createmodal(int $id, ?int $parameterr = 1, ?bool $from_store = false)
    {
        $this->fromStore = $from_store;
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
            $this->price = str_replace(',', '',  $getService->getPriceWithIvaRound(User::PRICE_RETAIL));
            $this->priceWithoutTax = str_replace(',', '',  $getService->getPriceWithoutIvaRound(User::PRICE_RETAIL));
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

        $order->products()->create([
            'product_id' => $this->service,
            'quantity' => $this->amount,
            'price' =>  $this->price,
            'price_without_tax' =>  $this->priceWithoutTax,
            'type' => $this->parameter,
        ]);

        // event(new OrderServiceCreated($order));

        $this->resetInputFields();
        $this->emit('serviceStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);
 
        return $this->redirectRoute($this->fromStore ? 'admin.store.all.edit' : 'admin.order.edit', $this->orderId);
   }

    public function render()
    {
        return view('backend.order.livewire.add-service');
    }
}
<?php

namespace App\Http\Livewire\Backend;

use App\Facades\Cart as CartFacade;
use Livewire\Component;

class Cart extends Component
{

    public $cart, $editAmount, $inputedit;

    protected $queryString = [
        'editAmount' => ['except' => FALSE],
    ];

    protected $listeners = ['increase' => '$refresh'];


    public function mount(): void
    {
        $this->cart = CartFacade::get();
    }

    private function init()
    {
        $this->cart = CartFacade::get();
    }



    public function increase($product_id)
    {
        $this->validate([
            'inputedit.*.amount' => 'numeric|sometimes',
        ]);

        dd($this->inputedit);

        if($this->inputedit){
            foreach($this->inputedit as $key => $productos){
                if(!empty($productos['amount']))
                {
                    dd($productos['amount']);
                }
            }
        }

    }

    public function updateEditAmount()
    {
        $this->init();
    }


    public function render()
    {
        return view('backend.cart.livewire.cart');
    }

    public function removeFromCart($productId): void
    {
        CartFacade::remove($productId);
        $this->cart = CartFacade::get();
        $this->emit('productRemoved');
    }

    public function clearCart(): void
    {
        CartFacade::clear();
        $this->emit('clearCart');
        $this->cart = CartFacade::get();
    }

    public function clearInput(): void
    {
        $this->inputedit = [];
    }

    public function checkout(): void
    {
        CartFacade::clear();
        $this->emit('clearCart');
        $this->cart = CartFacade::get();
    }

}

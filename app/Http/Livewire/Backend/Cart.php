<?php

namespace App\Http\Livewire\Backend;

use App\Facades\Cart as CartFacade;
use Livewire\Component;
use Session;

class Cart extends Component
{

    public $cart, $inputedit;

    protected $listeners = ['cartUpdated' => 'onCartUpdate'];


    public function mount(): void
    {
        $this->cart = CartFacade::get();
    }

    private function init()
    {
        $this->cart = CartFacade::get();
    }


    public function onCartUpdate()
    {
        // $this->cartItems = \Cart::session(auth()->id())->getContent()->toArray();
        $this->mount();
    }


    public function render()
    {

        // dd($this->cart['products']);
        // dd(Session::get('cart'));
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

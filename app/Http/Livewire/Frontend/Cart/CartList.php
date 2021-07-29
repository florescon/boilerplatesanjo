<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;
use App\Facades\Cart as CartFacade;

class CartList extends Component
{
    public $cart;

    protected $listeners = ['cartUpdatedList' => 'onCartUpdateList'];


    public function mount(): void
    {
        $this->cart = CartFacade::get();
    }

    public function onCartUpdateList()
    {
        $this->mount();
    }


    public function removeFromCartList($productId): void
    {
        CartFacade::remove($productId, 'products');
        $this->cart = CartFacade::get();
        $this->emit('productRemovedList');
    }

    public function render()
    {
        return view('frontend.cart.livewire.cart-list');
    }

}

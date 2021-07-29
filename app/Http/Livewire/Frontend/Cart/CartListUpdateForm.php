<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;

class CartListUpdateForm extends Component
{
    public function updateCartList()
    {
        $cart = CartFacade::get();
        $cart['products'] = $this->productCartEdit($this->item['id'], $cart['products']);

        $this->emit('cartUpdatedList');
    }


    public function render()
    {
        return view('frontend.cart.livewire.cart-list-update-form');
    }
}

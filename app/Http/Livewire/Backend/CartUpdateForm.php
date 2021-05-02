<?php

namespace App\Http\Livewire\Backend;

use Livewire\Component;
use App\Facades\Cart as CartFacade;
// use Session;

class CartUpdateForm extends Component
{

    public $item = [];
    public $quantity = 0;

    public function mount($item)
    {
        $this->item = $item;

        $this->quantity = $item['amount'];
    }


    public function updateCart()
    {

    	// dd($this->quantity);

    	// dd($this->quantity);
    	// dd(Session::get('cart')['products']);
    	// dd(CartFacade::get());

        $cart = CartFacade::get();

        $cart['products'] = $this->productCartEdit($this->item['id'], $cart['products']);


        $this->emit('cartUpdated');
    }


    private function productCartEdit($productId, $cartItems)
    {
        $amount = 1;
        $cartItems = array_map(function ($item) use ($productId, $amount) {
            if ($productId == $item['id']) {
                $item['amount'] = $this->quantity;
                $item['price'] += $item['price'];
            }

            return $item;
        }, $cartItems);

        return $cartItems;
    }


    public function render()
    {
        return view('backend.cart.livewire.cart-update-form');
    }

}

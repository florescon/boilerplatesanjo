<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;
use App\Facades\Cart as CartFacade;

class CartListUpdateForm extends Component
{
    public $item = [];
    public $quantity = 0;
    public string $typeCart;

    public function mount($item, string $typeCart)
    {
        $this->item = $item;

        $this->typeCart = $typeCart;

        $this->quantity = $item['amount'];
    }

    public function increment()
    {
        $this->quantity++;
        $this->updateCartList();
    }

    public function decrement()
    {
        if($this->quantity > 1){
            $this->quantity--;
            $this->updateCartList();
        }
    }

    public function updateCartList()
    {
        // dd($this->typeCart);
        $cart = CartFacade::get();
        $cart[$this->typeCart] = $this->productCartEdit($this->item['id'], $cart[$this->typeCart]);

        $this->emit('cartUpdatedList');
    }

    private function productCartEdit($productId, $cartItems)
    {
        $amount = 1;
        $cartItems = array_map(function ($item) use ($productId, $amount) {
            if ($productId == $item['id']) {
                $item['amount'] = $this->quantity;
            }

            return $item;
        }, $cartItems);

        return $cartItems;
    }

    public function render()
    {
        return view('frontend.cart.livewire.cart-list-update-form');
    }
}

<?php

namespace App\Http\Livewire\Backend;

use Livewire\Component;
use App\Facades\Cart as CartFacade;

class CartUpdatePriceForm extends Component
{
    public $item = [];
    public $price = 0;
    public string $typeCart;

    public function mount($item, string $typeCart)
    {
        $this->item = $item;

        $this->typeCart = $typeCart;

        $this->price = $item['price'];
    }

    public function updateCartPrice()
    {
        // dd($this->price);

        // dd($this->price);
        // dd(Session::get('cart')['products']);
        // dd(CartFacade::get());

        // dd($this->typeCart);
        $cart = CartFacade::get();

        $cart[$this->typeCart] = $this->productCartEdit($this->item['id'], $cart[$this->typeCart]);

        $this->emit('cartUpdated');
    }

    private function productCartEdit($productId, $cartItems)
    {
        $price = 1;
        $cartItems = array_map(function ($item) use ($productId, $price) {
            if ($productId == $item['id']) {
                $item['price'] = $this->price;
            }

            return $item;
        }, $cartItems);

        return $cartItems;
    }

    public function render()
    {
        return view('backend.cart.livewire.cart-update-price-form');
    }
}
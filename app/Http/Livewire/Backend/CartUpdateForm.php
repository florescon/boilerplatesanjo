<?php

namespace App\Http\Livewire\Backend;

use Livewire\Component;
use App\Facades\Cart as CartFacade;

class CartUpdateForm extends Component
{
    public $item = [];
    public $quantity = 0;
    public string $typeCart;

    protected $rules = [
        'quantity' => 'integer|min:1',
    ];

    public function mount($item, string $typeCart)
    {
        $this->item = $item;

        $this->typeCart = $typeCart;

        $this->quantity = $item['amount'];
    }

    public function updateCart()
    {
    	// dd($this->quantity);

    	// dd($this->quantity);
    	// dd(Session::get('cart')['products']);
    	// dd(CartFacade::get());

        // dd($this->typeCart);
        $cart = CartFacade::get();

        $cart[$this->typeCart] = $this->productCartEdit($this->item['id'], $cart[$this->typeCart]);

        $this->emit('cartUpdated');
    }

    private function productCartEdit($productId, $cartItems)
    {
        $this->validate();

        $amount = 1;
        $cartItems = array_map(function ($item) use ($productId, $amount) {
            if ($productId == $item['id']) {
                $item['amount'] = $this->quantity;
                $item['updated_at_'] = now();
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

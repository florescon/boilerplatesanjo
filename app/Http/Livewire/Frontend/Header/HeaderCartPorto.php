<?php

namespace App\Http\Livewire\Frontend\Header;

use Livewire\Component;
use App\Facades\Cart;

class HeaderCartPorto extends Component
{

    public $cartTotal = 0;
    public $cartTotalOrder = 0;

    protected $listeners = [
        'productAdded' => 'updateCartTotal',
        'productRemovedList' => 'updateCartTotal',
        'clearCart' => 'updateCartTotal'
    ];

    public function mount(): void
    {
        $this->cartTotal = count(Cart::get()['products']);
        $this->cartTotalOrder = count(Cart::get()['products_sale']);

    }

    public function updateCartTotal(): void
    {
        $this->cartTotal = count(Cart::get()['products']);
    }

    public function render()
    {
        return view('frontend.header.cart-porto');
    }

}

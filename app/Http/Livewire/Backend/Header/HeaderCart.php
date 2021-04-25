<?php

namespace App\Http\Livewire\Backend\Header;

use Livewire\Component;
use App\Facades\Cart;

class HeaderCart extends Component
{

    public $cartTotal = 0;

    protected $listeners = [
        'productAdded' => 'updateCartTotal',
        'productRemoved' => 'updateCartTotal',
        'clearCart' => 'updateCartTotal'
    ];

    public function mount(): void
    {
        $this->cartTotal = count(Cart::get()['products']);
    }

    public function updateCartTotal(): void
    {
        $this->cartTotal = count(Cart::get()['products']);
    }

    public function render()
    {
        return view('backend.header.cart');
    }
}

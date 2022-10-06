<?php

namespace App\Http\Livewire\Backend\Header;

use Livewire\Component;
use App\Facades\Cart;
use Illuminate\Support\Arr;

class HeaderCart extends Component
{
    public $cartTotal = 0;
    public $cartTotalSale = 0;

    protected $listeners = [
        'productAdded' => 'updateCartTotal',
        'productAddedSale' => 'updateCartTotalSale',
        'productRemoved' => 'updateCartTotal',
        'productRemovedSale' => 'updateCartTotalSale',
        'clearCart' => 'updateCartTotal',
        'clearCartSale' => 'updateCartTotalSale',
        'clearCartAll' => 'updateCartTotalAll',
    ];

    public function mount(): void
    {
        $this->cartTotal = Arr::exists(Cart::get(), 'products') ? count(Cart::get()['products']) : 0;
        $this->cartTotalSale = Arr::exists(Cart::get(), 'products_sale') ? count(Cart::get()['products_sale']) : 0;
    }

    public function updateCartTotal(): void
    {
        $this->cartTotal = count(Cart::get()['products']);
    }

    public function updateCartTotalSale(): void
    {
        $this->cartTotalSale = count(Cart::get()['products_sale']);
    }

    public function updateCartTotalAll(): void
    {
        $this->cartTotal = count(Cart::get()['products']);
        $this->cartTotalSale = count(Cart::get()['products_sale']);
    }

    public function render()
    {
        return view('backend.header.cart');
    }
}

<?php

namespace App\Http\Livewire\Backend;

use Livewire\Component;
use App\Facades\Cart as CartFacade;

class CartShowSaleTotal extends Component
{
    protected $listeners = ['cartUpdated' => 'init'];

    public function mount()
    {
        $this->total = CartFacade::totalSale();
    }

    public function init()
    {
        $this->total = CartFacade::totalSale();
    }

    public function render()
    {
        return view('backend.cart.livewire.cart-show-sale-total');
    }
}

<?php

namespace App\Http\Livewire\Backend;

use Livewire\Component;
use App\Facades\Cart as CartFacade;

class CartShowTotal extends Component
{
    protected $listeners = ['cartUpdated' => 'init'];

    public function mount()
    {
        $this->total = CartFacade::totalOrder();
    }

    public function init()
    {
        $this->total = CartFacade::totalOrder();
    }

    public function render()
    {
        return view('backend.cart.livewire.cart-show-total');
    }
}

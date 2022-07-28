<?php

namespace App\Http\Livewire\Backend;

use Livewire\Component;
use App\Facades\Cart as CartFacade;

class CartShowPriceTotal extends Component
{
    public string $typeCart;

    public string $total;

    public string $totalWithIva;

    protected $listeners = ['cartUpdated' => 'init'];

    public function mount(string $typeCart)
    {
        $this->typeCart = $typeCart;
        $this->total = CartFacade::totalPriceOrder($typeCart);
        $this->totalWithIva = CartFacade::totalPriceOrderWithIva($typeCart);
    }

    public function init()
    {
        $this->total = CartFacade::totalPriceOrder($this->typeCart);
        $this->totalWithIva = CartFacade::totalPriceOrderWithIva($this->typeCart);
    }

    public function render()
    {
        return view('backend.cart.livewire.cart-show-price-total');
    }
}

<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;
use App\Facades\Cart as CartFacade;

class CartList extends Component
{
    public $cart;

    protected $listeners = ['cartUpdatedList' => 'onCartUpdateList'];


    public function mount(): void
    {
        $this->cart = CartFacade::get();
    }

    public function onCartUpdateList()
    {
        $this->mount();
    }


    public function removeFromCartList($productId): void
    {
        $this->removeRedirectLink();

        CartFacade::remove($productId, 'products');
        $this->cart = CartFacade::get();
        $this->emit('productRemovedList');
    }


    public function removeFromCartListSale($productId): void
    {

        $this->removeRedirectLink();

        CartFacade::remove($productId, 'products_sale');
        $this->cart = CartFacade::get();
        $this->emit('productRemovedList');
    }


    public function removeRedirectLink()
    {
        if(count($this->cart['products']) > 0 && count($this->cart['products_sale'])){
            return redirect()->route('frontend.cart.index');
        }

    }


    public function render()
    {
        return view('frontend.cart.livewire.cart-list');
    }

}

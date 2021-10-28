<?php

namespace App\Http\Livewire\Backend\Store\Pos;

use Livewire\Component;
use App\Facades\Cart as CartFacade;

class CartPos extends Component
{
    public $cart;

    protected $listeners = ['onProductCartAdded' => 'render'];

    public function removeFromOrderList($productId): void
    {
        $this->removeRedirectLink();

        CartFacade::remove($productId, 'products');
        $this->cart = CartFacade::get();
        $this->emit('onProductCartAdded');
        $this->emit('productRemoved');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function removeFromSaleList($productId): void
    {
        $this->removeRedirectLink();

        CartFacade::remove($productId, 'products_sale');
        $this->cart = CartFacade::get();
        $this->emit('onProductCartAdded');
        $this->emit('productRemovedSale');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }


    public function removeRedirectLink()
    {
        $this->cart = CartFacade::get();

        if(count($this->cart['products']) && count($this->cart['products_sale'])){
            return redirect()->route('admin.store.pos');
        }
    }

    public function render()
    {
        $cartVar = CartFacade::get();
        return view('backend.store.pos.cart-pos')->with(compact('cartVar'));
    }
}

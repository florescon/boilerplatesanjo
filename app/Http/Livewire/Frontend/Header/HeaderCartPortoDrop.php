<?php

namespace App\Http\Livewire\Frontend\Header;

use App\Facades\Cart as CartFacade;
use Livewire\Component;
use App\Models\Order;
use App\Models\ProductOrder;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class HeaderCartPortoDrop extends Component
{

    public $cart;
    public $cartTotal = 0;
    public $cartTotalOrder = 0;

    protected $listeners = [
        'productAdded' => 'updateModalSaleCartTotal',
        'productRemovedList' => 'updateModalSaleCartTotal',
        'productRemovedSaleList' => 'updateModalSaleCartTotal',
    ];

    public function init()
    {
        $this->cart = CartFacade::get();
    }

    public function updateModalCartTotal(): void
    {
        $this->cartTotal = count(CartFacade::get()['products']);
    }


    public function updateModalSaleCartTotal(): void
    {
        $this->cartTotalOrder = count(CartFacade::get()['products_sale']);
    }

    public function render()
    {
        $this->cart = array_slice(CartFacade::get()['products'], 0, 4);
        $this->cartTotal = count(CartFacade::get()['products']);
        $this->cartTotalOrder = count(CartFacade::get()['products_sale']);

        // return view('frontend.header.cart-porto-drop');
        return view('frontend.includes_ga.modal-cart');
    }
}

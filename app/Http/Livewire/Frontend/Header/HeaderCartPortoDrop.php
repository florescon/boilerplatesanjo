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
        'productAdded' => 'init',
        'productRemovedList' => 'init',
    ];


    public function init()
    {
        $this->cart = CartFacade::get();
    }

    public function render()
    {
        $this->cart = CartFacade::get()['products'];
        $this->cartTotal = count(CartFacade::get()['products']);
        $this->cartTotalOrder = count(CartFacade::get()['products_sale']);

        return view('frontend.header.cart-porto-drop');
    }
}

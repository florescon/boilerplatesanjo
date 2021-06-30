<?php

namespace App\Http\Livewire\Backend;

use App\Facades\Cart as CartFacade;
use Livewire\Component;
use App\Models\Order;
use App\Models\ProductOrder;
use Session;
use Carbon\Carbon;

class Cart extends Component
{

    public $cart, $inputedit, $comment, $sale, $user, $archive;

    public $isVisible = false;

    protected $listeners = ['cartUpdated' => 'onCartUpdate', 'selectedCompanyItem'];


    public function selectedCompanyItem($item)
    {

        $this->init();

        if ($item) {
            $this->user = $item;
        }
        else
            $this->user = null;
    }

    public function updatedIsVisible()
    {
        $this->init();
    }


    public function mount(): void
    {
        $this->cart = CartFacade::get();
    }

    private function init()
    {
        $this->cart = CartFacade::get();
    }


    public function onCartUpdate()
    {
        // $this->cartItems = \Cart::session(auth()->id())->getContent()->toArray();
        $this->mount();
    }


    public function render()
    {

        // dd($this->cart['products']);
        // dd(Session::get('cart'));
        return view('backend.cart.livewire.cart');
    }

    public function removeFromCart($productId): void
    {
        CartFacade::remove($productId);
        $this->cart = CartFacade::get();
        $this->emit('productRemoved');
    }


    public function removeFromCartSale($productId): void
    {
        CartFacade::removeSale($productId);
        $this->cart = CartFacade::get();
        $this->emit('productRemovedSale');
    }

    public function clearCart(): void
    {
        CartFacade::clear();
        $this->emit('clearCart');
        $this->emit('clearCartSale');
        $this->cart = CartFacade::get();
    }

    public function clearInput(): void
    {
        $this->inputedit = [];
    }

    public function checkout(): void
    {
        // dd(CartFacade::get()['products']);

        // dd($this->isVisible);

        $order = new Order();
        $order->user_id = $this->isVisible == true  ? null : $this->user;
        $order->comment = $this->comment;
        $order->date_entered = Carbon::now()->format('Y-m-d');
        $order->type = 1;
        $order->approved = 1;
        $order->save();

        // dd($order->id);

        $cart = CartFacade::get()['products'];

        foreach ($cart as  $item) {

            if($item->amount >= 1){
                $order->product_order()->create([
                    'product_id' => $item->id,
                    'quantity' => $item->amount,
                    'price' =>  !is_null($item->price) || $item->price != 0 ? 
                                    $item->price : $item->parent->price,
                    'type' => 1,
                ]);
            }

        }

        CartFacade::clear();
        $this->emit('clearCart');
        $this->cart = CartFacade::get();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Order created'), 
        ]);

    }

}

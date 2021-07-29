<?php

namespace App\Http\Livewire\Backend;

use App\Facades\Cart as CartFacade;
use Livewire\Component;
use App\Models\Order;
use App\Models\ProductOrder;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{

    public $cart, $inputedit, $comment, $sale, $user, $archive;

    public $isVisible = false;

    protected $listeners = ['selectedCompanyItem', 'cartUpdated' => '$refresh'];

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


    // public function mount(): void
    // {
    //     $this->cart = CartFacade::get();
    // }


    private function init()
    {
        $this->cart = CartFacade::get()['products'];
    }

    public function onCartUpdate()
    {
        // $this->cartItems = \Cart::session(auth()->id())->getContent()->toArray();
        $this->init();
    }

    public function render()
    {

        // dd(Session::get('cart'));
        // return view('backend.cart.livewire.cart');

        $cartVar = CartFacade::get();

        return view('backend.cart.livewire.cart')->with(compact('cartVar'));

    }

    public function removeFromCart($productId, $typeCart)
    {

        CartFacade::remove($productId, $typeCart);

        return redirect()->route('admin.cart.index');

        // $this->cart = CartFacade::get()[$typeCart];

        // if($typeCart == 'products'){
        //     $this->emit('productRemoved');
        // }
        // elseif($typeCart == 'products_sale'){
        //     $this->emit('productRemovedSale');
        // }
    }

    public function clearCartAll(): void
    {
        CartFacade::clear();
        $this->emit('clearCartAll');
        $this->cart = CartFacade::get();
    }

    public function clearInput(): void
    {
        $this->inputedit = [];
    }


    private function defineType (bool $order, bool $sale){
        
        if($sale){
            if($order){
                return 3;                
            }
            return 2;    
        }
        else{
            return 1;
        }

    }

    public function checkout(): void
    {
        // dd(CartFacade::get()['products']);

        // dd($this->isVisible);


        $cart = CartFacade::get()['products'];
        $cartSale = CartFacade::get()['products_sale'];

        $order = new Order();
        $order->user_id = $this->isVisible == true  ? null : $this->user;
        $order->comment = $this->comment;
        $order->date_entered = Carbon::now()->format('Y-m-d');
        $order->type = $this->defineType(count($cart), count($cartSale));
        $order->audi_id = Auth::id();
        $order->approved = 1;
        $order->save();

        // dd($order->id);

        if(count($cart)){
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
        }
    
        if(count($cartSale)){
            foreach ($cartSale as  $item) {
    
                if($item->amount >= 1){
                    $order->product_order()->create([
                        'product_id' => $item->id,
                        'quantity' => $item->amount,
                        'price' =>  !is_null($item->price) || $item->price != 0 ? 
                                        $item->price : $item->parent->price,
                        'type' => 2,
                    ]);
                }
    
            }
        }

        CartFacade::clear();
        $this->emit('clearCartAll');
        $this->cart = CartFacade::get();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Order created'), 
        ]);

    }

}

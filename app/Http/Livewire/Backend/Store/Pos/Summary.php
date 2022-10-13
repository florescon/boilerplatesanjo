<?php

namespace App\Http\Livewire\Backend\Store\Pos;

use Livewire\Component;
use App\Facades\Cart as CartFacade;
use App\Domains\Auth\Models\User;
use App\Models\Order;
use App\Events\Order\OrderCreated;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Summary extends Component
{
    public $cart, $payment, $payment_method, $comment;

    protected $listeners = ['selectPaymentMethod', 'selectedCompanyItem', 'cartUpdated' => '$refresh', 'cartCheckout' => 'cart', 'selected' => 'render'];

    public function selectedCompanyItem($user)
    {
        $this->init();

        CartFacade::clearDepartament();

        if ($user) {
            $this->user = $user;
            CartFacade::addUser(User::select('id', 'name')->
                with(array('customer' => function($query) {
                    $query->select('id', 'user_id', 'type_price');
                }))->get()
                ->find($user));
            $this->emit('selected');
        }
        else{
            $this->user = null;
        }

        $this->redirectLink();
    }

    private function init()
    {
        $this->cart = CartFacade::get()['products'];
    }

    public function redirectLink()
    {
        return redirect()->route('admin.store.order');
    }

    public function clearUser()
    {
        CartFacade::clearUser();
        $this->cart = CartFacade::get();

        $this->redirectLink();
    }

    public function clearCartAll(): void
    {
        CartFacade::clear();
        $this->emit('clearCartAll');
        $this->cart = CartFacade::get();
    }

    private function defineType (bool $order, bool $sale)
    {
        if($sale){
            if($order){
                return 3;                
            }
            return 2;    
        }
        else{
            return 5;
        }
    }

    public function selectPaymentMethod($payment_method)
    {
        if ($payment_method){
            $this->payment_method = $payment_method;
        }
        else{
            $this->payment_method = null;
        }
    }

    public function checkout()
    {
        $cart = CartFacade::get()['products'];
        $cartSale = CartFacade::get()['products_sale'];

        $cartuser = CartFacade::get()['user'][0] ?? null;
        $cartdepartament = CartFacade::get()['departament'][0] ?? null;

        if($cartuser != null){
            $type_price = $cartuser->customer->type_price ?? 'retail';
        }

        if($cartdepartament != null){
            $type_price = $cartdepartament->type_price ?? 'retail';
        }

        $order = new Order();
        $order->user_id = $cartuser->id ?? null;
        $order->departament_id = $cartdepartament->id ?? null;
        $order->comment = $this->comment;
        $order->date_entered = Carbon::now()->format('Y-m-d');
        $order->type = $this->defineType(count($cart), count($cartSale));
        $order->audi_id = Auth::id();
        $order->from_store = true;
        $order->approved = 1;
        $order->save();

        event(new OrderCreated($order));

        if($this->payment && $this->payment_method){
            $order->orders_payments()->create([
                'name' => 'pago',
                'amount' => $this->payment,
                'type' => 'income',
                'date_entered' => today(),
                'from_store' => true,
                'payment_method_id' => $this->payment_method,
                'audi_id' => Auth::id(),
            ]);
        }

        if(count($cart)){
            foreach ($cart as $item) {
                if($item->amount >= 1){
                    $order->product_order()->create([
                        'product_id' => $item->id,
                        'quantity' => $item->amount,
                        'price' =>  ($cartuser || $cartdepartament) ? $item->getPriceWithIva($type_price) : $item->getPriceWithIva(),
                        'type' => 5,
                    ]);
                }
            }
        }
    
        if(count($cartSale)){
            foreach ($cartSale as $item) {
                if($item->amount >= 1){
                    $order->product_order()->create([
                        'product_id' => $item->id,
                        'quantity' => $item->amount,
                        'price' =>  ($cartuser || $cartdepartament) ? $item->getPriceWithIva($type_price) : $item->getPriceWithIva(),
                        'type' => 2,
                    ]);
                }
            }
        }

        CartFacade::clear();
        
        return redirect()->route('admin.order.edit', $order->id);
    }

    public function render()
    {
        $cartVar = CartFacade::get();

        return view('backend.store.pos.summary')->with([
            'cartVar' => $cartVar,
        ]);
    }
}

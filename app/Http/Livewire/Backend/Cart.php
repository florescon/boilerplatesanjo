<?php

namespace App\Http\Livewire\Backend;

use App\Facades\Cart as CartFacade;
use Livewire\Component;
use App\Models\Order;
use App\Models\ProductOrder;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domains\Auth\Models\User;
use App\Models\Departament;
use App\Events\Order\OrderCreated;

class Cart extends Component
{
    public $cart, $inputedit, $comment, $sale, $user, $departament, $payment, $payment_method, $archive;

    public bool $fromStore = false;

    public $isVisible = false;

    protected $listeners = ['selectPaymentMethod', 'selectedCompanyItem', 'selectedDeparament', 'cartUpdated' => '$refresh', 'selected' => 'render'];

    //prohibited_unless:departament,
    // required_without:departament|
    protected $rules = [
        'user' => 'prohibited_unless:departament,null',
        'departament' => 'prohibited_unless:user,null',
        // 'payment' => 'required_with:user,departament',
        // 'payment_method' => 'required_with:user,departament|integer',
    ];

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

        return redirect()->route('admin.cart.index');
    }

    public function selectedDeparament($departament)
    {
        $this->init();

        CartFacade::clearUser();

        if ($departament) {
            $this->departament = $departament;
            CartFacade::addDepartament(Departament::select('id', 'name', 'type_price')->get()
                ->find($departament));
            $this->emit('selected');
        }
        else{
            $this->departament = null;
        }

        return redirect()->route('admin.cart.index');
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

    public function updatedIsVisible()
    {
        $this->init();
    }

    private function init()
    {
        $this->cart = CartFacade::get()['products'];
    }

    public function onCartUpdate()
    {
        $this->init();
    }

    public function removeFromCart($productId, $typeCart)
    {
        CartFacade::remove($productId, $typeCart);

        return redirect()->route('admin.cart.index');
    }

    public function clearCartAll(): void
    {
        CartFacade::clear();
        $this->emit('clearCartAll');
        $this->cart = CartFacade::get();
    }

    public function clearUser()
    {
        CartFacade::clearUser();
        $this->cart = CartFacade::get();

        return redirect()->route('admin.cart.index');
    }

    public function clearDepartament()
    {
        CartFacade::clearDepartament();
        $this->cart = CartFacade::get();

        return redirect()->route('admin.cart.index');
    }

    public function clearInput(): void
    {
        $this->inputedit = [];
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
            return 1;
        }
    }

    public function checkout(): void
    {
        if(!$this->isVisible){
            $this->validate();
        }

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
        $order->user_id = $this->isVisible == true  ? null : ($cartuser->id ?? null);
        $order->departament_id = $this->isVisible == true  ? null : ($cartdepartament->id ?? null);
        $order->comment = $this->comment;
        $order->date_entered = Carbon::now()->format('Y-m-d');
        $order->type = $this->defineType(count($cart), count($cartSale));
        $order->audi_id = Auth::id();
        $order->from_store = $this->fromStore == true ? true : null;
        $order->approved = 1;
        $order->save();

        event(new OrderCreated($order));

        if($this->payment && $this->payment_method){
            $order->orders_payments()->create([
                'name' => 'pago',
                'amount' => $this->payment,
                'type' => 'income',
                'date_entered' => today(),
                'from_store' => $this->fromStore == true ? true : null,
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
                        'price' =>  ($cartuser || $cartdepartament) ? $item->getPrice($type_price) : $item->parent->price,
                        'type' => 1,
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
                        'price' =>  ($cartuser || $cartdepartament) ? $item->getPrice($type_price) : $item->parent->price,
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

    public function render()
    {
        $cartVar = CartFacade::get();
        // dd($cartVar);
        return view('backend.cart.livewire.cart')->with(compact('cartVar'));
    }
}
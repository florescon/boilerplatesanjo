<?php

namespace App\Http\Livewire\Backend\Cartdb;

use Livewire\Component;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Summary;
use App\Domains\Auth\Models\User;
use App\Models\Cart;
use App\Models\OrderStatusDelivery;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

class Summarydb extends Component
{
    public ?string $type = '';

    public ?int $branchIdSummary = 0;

    public bool $isMain = false;
    public bool $flowchart = false;

    public $description = '';
    public $info_customer = '';
    public $request = '';

    public $summary;

    public $chartUrl;

    public $customer, $phone, $address, $rfc;

    protected $listeners = ['selectedCompanyItem', 'cartUpdated' => '$refresh'];

    public function mount(string $typeSummary, ?int $branchIdSummary = 0, ?bool $isMain = false, ?bool $flowchart = false)
    {

        $this->chartUrl = Route::is('admin.order.quotation_chart');

        $this->isMain = $isMain;
        $this->flowchart = $flowchart;
        $this->type = $typeSummary;
        $this->branchId = $branchIdSummary;
        $this->summary = Summary::where('type', $typeSummary)->where('branch_id', $branchIdSummary)->where('user_id', Auth::id())->first() ?? null;
        $this->description = $this->summary->description ?? '';
        $this->info_customer = $this->summary->info_customer ?? '';
        $this->request = $this->summary->request ?? '';
        $this->customer = $this->summary->customer_id ?? null;
        $this->type_price = $this->summary->customer->customer->type_price ?? 'retail';
        $this->phone = $this->summary->customer->customer->phone ?? null;
        $this->address = $this->summary->customer->customer->address ?? null;
        $this->rfc = $this->summary->customer->customer->rfc ?? null;
    }

    public function selectedCompanyItem($customer)
    {
        if ($customer) {
            $this->customer = $customer;

            $customerDB = User::where('id', $customer)->first();

            $summary = Summary::updateOrCreate(
                ['branch_id' => $this->branchId, 'type' => $this->type, 'user_id' => Auth::id()],
                ['customer_id' => $this->customer, 'type_price' => optional($customerDB->customer)->type_price ?? User::PRICE_RETAIL]
            );

             // $this->emit('updatePrices');
        }
        else{
            $this->customer = null;
        }

        $this->redirectLink();

    }

    public function redirectLink()
    {
        if($this->isMain){
            if($this->chartUrl){
                return redirect()->route('admin.order.quotation_chart');
            }
            else{
                return redirect()->route('admin.order.quotation');
            }
        }

        $link = 'admin.store.'.$this->type;

        return redirect()->route($link);
    }

    private function getProducts()
    {
        return $products = Cart::with('product.color', 'product.parent', 'product.size')->where('type', $this->type)->where('branch_id', $this->branchId)->where('user_id', Auth::id())->get();
    }

    private function clearSummary()
    {
        $this->summary->delete();
    }

    public function clearUser()
    {
        $deleteCustomer = DB::table('summaries')->where('id', $this->summary->id)->update(['customer_id' => null, 'type_price' => User::PRICE_RETAIL]);

        foreach($this->getProducts() as $product){
            $price = $product->product->getPriceWithIvaRound(User::PRICE_RETAIL);
            $priceWithoutIva = $product->product->getPriceWithoutIvaRound(User::PRICE_RETAIL);
            $product->update(['price' => $price, 'price_without_tax' => $priceWithoutIva]);
        }

        $this->redirectLink();
    }

    public function updatedDescription()
    {
        $this->emit('updatePrices');

        $summary = Summary::updateOrCreate(
            ['branch_id' => $this->branchId, 'type' => $this->type, 'user_id' => Auth::id()],
            ['description' => $this->description]
        );
    }

    public function updatedInfoCustomer()
    {
        $summary = Summary::updateOrCreate(
            ['branch_id' => $this->branchId, 'type' => $this->type, 'user_id' => Auth::id()],
            ['info_customer' => $this->info_customer]
        );
    }

    public function updatedRequest()
    {
        $summary = Summary::updateOrCreate(
            ['branch_id' => $this->branchId, 'type' => $this->type, 'user_id' => Auth::id()],
            ['request' => $this->request]
        );
    }

    public function checkout()
    {

        $getProducts = $this->getProducts();

        $getProducts->when($getProducts->count(), function ($getProducts) {

            $typeOrder = typeInOrder($this->type);

            $order = new Order();
            $order->user_id = $this->customer ?? null;
            $order->comment = $this->description;
            $order->info_customer = $this->info_customer;
            $order->request = $this->request;
            $order->date_entered = Carbon::now()->format('Y-m-d');
            $order->type = typeInOrder($this->type);
            $order->audi_id = Auth::id();
            $order->from_store = $this->isMain ? null : true;
            $order->flowchart = $this->flowchart ? true : null;
            $order->approved = 1;
            $order->branch_id = $this->branchId;
            $order->from_quotation = ($typeOrder == 6) ? true : false;
            $order->save();

            $product_type = 'product_'.$this->type;

            foreach($this->getProducts() as $product){

                $order->products()->create([
                    'product_id' => $product->product_id,
                    'quantity' => $product->quantity,
                    'price' =>  $typeOrder != 7 ? $product->price : 0,
                    'price_without_tax' => $typeOrder != 7 ? $product->price_without_tax : 0,
                    'comment' => $product->comment,
                    'type' => typeInOrder($this->type),
                ]);
            }

            if(($this->branchId != 0) && (typeInOrder($this->type) == 2 )){
                $this->saleReadyForDelivery($order);
            }

            if(($this->branchId != 0) && (typeInOrder($this->type) == 5 )){
                $this->requestReadyForDelivery($order);
            }

            $this->clearSummary();
            $this->emit('clearAllProducts');


            if($this->isMain){
                if($this->chartUrl){
                    return redirect()->route('admin.order.edit_chart', $order->id);
                }
                else{
                    return redirect()->route('admin.order.edit', $order->id);
                }
            }

            return redirect()->route('admin.store.all.edit', $order->id);
        });
    }    

    public function saleReadyForDelivery($order)
    {
        $order ? $order->orders_delivery()->create(['type' => OrderStatusDelivery::DELIVERED, 'audi_id' => Auth::id()]) : '';
    }

    public function requestReadyForDelivery($order)
    {
        $order ? $order->orders_delivery()->create(['type' => OrderStatusDelivery::PENDING, 'audi_id' => Auth::id()]) : '';
    }

    public function render()
    {
        $countProducts = DB::table('carts')->where('type', $this->type)->where('branch_id', $this->branchId)->where('user_id', Auth::id())->count();

        return view('backend.cartdb.summary', [
            'countProducts' => $countProducts,
        ]);
    }
}

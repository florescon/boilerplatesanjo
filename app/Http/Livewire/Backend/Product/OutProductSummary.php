<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SummaryProduct;
use App\Domains\Auth\Models\User;
use App\Models\CartProduct;
use App\Models\OrderStatusDelivery;
use App\Models\Out;
use App\Models\Product;
use Carbon\Carbon;

class OutProductSummary extends Component
{
    public ?string $type = '';

    public ?int $branchIdSummary = 0;

    public bool $isMain = false;

    public $description = '';

    public $summary;

    public $customer, $phone, $address, $rfc;

    protected $listeners = ['selectedCompanyItem', 'cartUpdated' => '$refresh'];

    public function mount()
    {
        $this->branchId = 0;
        $this->summary = SummaryProduct::where('user_id', Auth::id())->first() ?? null;
        $this->description = $this->summary->description ?? '';
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

            $summary = SummaryProduct::updateOrCreate(
                ['user_id' => Auth::id()],
                ['customer_id' => $this->customer]
            );

        }
        else{
            $this->customer = null;
        }

        $this->redirectLink();

    }

    public function redirectLink()
    {
        return redirect()->route('admin.product.out');
    }

    private function getProducts()
    {
        return $products = CartProduct::with('product')->where('user_id', Auth::id())->get();
    }

    private function clearSummary()
    {
        $this->summary->delete();
    }

    public function clearUser()
    {
        $deleteCustomer = DB::table('summary_products')->where('id', $this->summary->id)->update(['customer_id' => null]);

        $this->redirectLink();
    }

    public function updatedDescription()
    {
        $summary = SummaryProduct::updateOrCreate(
            ['user_id' => Auth::id()],
            ['description' => $this->description]
        );
    }

    public function checkout()
    {
        $getProducts = $this->getProducts();

        foreach ($getProducts as $product) {
            if ($product->product->stock < $product->quantity) {
                return $this->emit('swal:modal', [
                    'icon' => 'error',
                    'title' => __('Una o más productos no cuenta con la suficiente existencia'),
                ]);
            }
        }

        $getProducts->when($getProducts->count(), function ($getProducts) {

            $out = new Out();
            $out->customer_id = $this->customer ?? null;
            $out->description = $this->description;
            $out->type = 'out_product';
            $out->user_id = Auth::id();
            $out->save();

            foreach($this->getProducts() as $product){

                $productDecrement = Product::withTrashed()->find($product->product_id);
                
                if($product->quantity > 0){
                    $productDecrement->decrement('stock', abs($product->quantity));
                }

                $out->feedstocks()->create([
                    'product_id' => $product->product_id,
                    'quantity' => $product->quantity,
                    'price' =>  $product->price,
                    'comment' => $product->comment,
                    'type' => $product->type,
                ]);
            }

            $this->clearSummary();
            $this->emit('clearAllProducts');

            $this->emit('redirectToTicketOut', route('admin.product.ticket_out', $out->id));

        });
    }    

    public function render()
    {
        $countProducts = DB::table('cart_products')->where('user_id', Auth::id())->count();

        return view('backend.product.livewire.out-product-summary', [
            'countProducts' => $countProducts,
        ]);
    }
}

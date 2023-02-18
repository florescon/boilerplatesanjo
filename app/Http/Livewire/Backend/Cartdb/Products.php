<?php

namespace App\Http\Livewire\Backend\Cartdb;

use Livewire\Component;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Domains\Auth\Models\User;
use App\Models\Cart;

class Products extends Component
{
    public ?string $type = '';

    public ?int $branchId = 0;
    public bool $showPriceWithoutTax = false;

    protected $listeners = ['selectedCompanyItem' => 'updatePrices', 'clearAllProducts', 'cartUpdated' => '$refresh'];

    public function mount(string $type, ?int $branchId = 0)
    {
        $this->type = $type;
        $this->branchId = $branchId;
    }

    public function clearAllProducts()
    {
        DB::table('carts')->where('type', $this->type)->where('branch_id', $this->branchId)->where('user_id', Auth::id())->delete();
    }

    public function removeProduct($productId): void
    {
        $delete = DB::table('carts')->where('id', $productId)->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    private function getProducts()
    {
        return $products = Cart::with('product.color', 'product.parent', 'product.size')->where('type', $this->type)->where('branch_id', $this->branchId)->where('user_id', Auth::id())->get();
    }    

    public function updatePrices(?int $getCustomer = null)
    {
        if(!$getCustomer){
            $customer = DB::table('summaries')->where('type', $this->type)->where('branch_id', $this->branchId)->where('user_id', Auth::id())->first() ?? null;

            $customerPrice = $customer->type_price;
        }
        else{
            $customerDB = User::where('id', $getCustomer)->first();
            $customerPrice = optional($customerDB->customer)->type_price ?? 'retail';

        }

        foreach($this->getProducts() as $product){
            if($product->product->isProduct()){

                $price = $product->product->getPriceWithIva($customerPrice);

                $product->update(['price' => $price, 'price_without_tax' => priceWithoutIvaIncluded($price)]);
            }
        }
    }

    public function render()
    {
        $customer = DB::table('summaries')->where('type', $this->type)->where('branch_id', $this->branchId)->where('user_id', Auth::id())->first() ?? null;

        return view('backend.cartdb.products', [
            'products' => $this->getProducts(),
            'type_price' => $customer->type_price ?? User::PRICE_RETAIL,
        ]);
    }
}

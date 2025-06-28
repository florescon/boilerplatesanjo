<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Domains\Auth\Models\User;
use App\Models\CartProduct;

class OutProduct extends Component
{
    public ?int $branchId = 0;
    public bool $showPriceWithoutTax = false;

    protected $listeners = ['clearAllProducts', 'cartUpdated' => 'render'];

    public function clearAllProducts()
    {
        DB::table('cart_products')->where('user_id', Auth::id())->delete();

        return redirect()->route('admin.product.out');
    }

    public function removeMaterial($productId): void
    {
        $delete = DB::table('cart_products')->where('id', $productId)->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    private function getProducts()
    {
        return $products = CartProduct::with('product')->where('user_id', Auth::id())->get();
    }    

    public function render()
    {
        $customer = DB::table('summaries')->where('branch_id', $this->branchId)->where('user_id', Auth::id())->first() ?? null;

        return view('backend.product.livewire.out-product', [
            'products' => $this->getProducts(),
            'type_price' => $customer->type_price ?? User::PRICE_RETAIL,
        ]);
    }
}

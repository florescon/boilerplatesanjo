<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;
use App\Models\Material;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Domains\Auth\Models\User;
use App\Models\CartFeedstock;

class OutFeedstock extends Component
{
    public ?int $branchId = 0;
    public bool $showPriceWithoutTax = false;

    protected $listeners = ['clearAllProducts', 'cartUpdated' => 'render'];

    public function clearAllProducts()
    {
        DB::table('cart_feedstocks')->where('user_id', Auth::id())->delete();

        return redirect()->route('admin.material.out');
    }

    public function removeMaterial($productId): void
    {
        $delete = DB::table('cart_feedstocks')->where('id', $productId)->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    private function getProducts()
    {
        return $products = CartFeedstock::with('material.color', 'material.unit', 'material.size')->where('user_id', Auth::id())->get();
    }    

    public function render()
    {
        $customer = DB::table('summaries')->where('branch_id', $this->branchId)->where('user_id', Auth::id())->first() ?? null;

        return view('backend.material.livewire.out-feedstock', [
            'products' => $this->getProducts(),
            'type_price' => $customer->type_price ?? User::PRICE_RETAIL,
        ]);
    }
}

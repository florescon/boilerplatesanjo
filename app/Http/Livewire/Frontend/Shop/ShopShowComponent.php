<?php

namespace App\Http\Livewire\Frontend\Shop;

use Livewire\Component;
use App\Models\Frontend\Product;
use App\Models\Line;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class ShopShowComponent extends Component
{

    public $origPhoto, $product_id;
    public ?bool $getWhislist; 
    // public bool $status;

    public function mount(Product $product)
    {
        $product->load('favorite');
        $this->product_id = $product->id;
        $this->origPhoto = $product->file_name;
        // $this->getWhislist = optional($product->favoriteByAuth(Auth::id()))->status;
    }

    public function wishlist()
    {
        if (!Auth::check()) {
           return redirect()->route('frontend.auth.login');
        }
        else
            $favorite = Favorite::where('product_id', $this->product_id)->where('audi_id', Auth::id())->first();

            $product = Product::findOrFail($this->product_id);
            $product->favorite()->updateOrCreate(
                ['product_id' => $this->product_id, 'audi_id' => Auth::id()], 
                [
                    'status' => (optional($favorite)->status == true) ? false : true,
                    'audi_id' => Auth::id(),
                ],
            );

            return redirect()->back();

    }

    public function render()
    {
        // $product = Product::where('slug', $this->slug)->first();

        $model = Product::with('pictures')->findOrFail($this->product_id);

        $featured_products = Product::with('line')->whereNull('parent_id')->orderBy('updated_at', 'desc')->onlyActive()->limit(6)->get();

        $attributes = Product::with('children')->findOrFail($this->product_id);

        $lines = Line::inRandomOrder()->limit(4)->get();

		return view('frontend.shop.livewire.shop-show-component')->with(compact('model', 'attributes', 'lines', 'featured_products'));

    }

}

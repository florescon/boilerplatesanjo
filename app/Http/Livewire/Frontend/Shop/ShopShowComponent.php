<?php

namespace App\Http\Livewire\Frontend\Shop;

use Livewire\Component;
use App\Models\Product;
use App\Models\Line;

class ShopShowComponent extends Component
{

    public $origPhoto, $product_id;


    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->origPhoto = $product->file_name;
    }

    public function render()
    {
        // $product = Product::where('slug', $this->slug)->first();

        $model = Product::with('pictures')->findOrFail($this->product_id);

        $featured_products = Product::with('line')->whereNull('parent_id')->orderBy('updated_at', 'desc')->limit(6)->get();

        $attributes = Product::with('children')->findOrFail($this->product_id);

        $lines = Line::inRandomOrder()->limit(4)->get();

		return view('frontend.shop.livewire.shop-show-component')->with(compact('model', 'attributes', 'lines', 'featured_products'));

    }

}

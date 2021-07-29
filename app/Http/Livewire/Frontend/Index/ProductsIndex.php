<?php

namespace App\Http\Livewire\Frontend\Index;

use Livewire\Component;
use App\Models\Frontend\Product;

class ProductsIndex extends Component
{

    public function render()
    {

        $featured_products = Product::with('line')->whereNull('parent_id')->orderBy('updated_at', 'desc')->onlyActive()->limit(6)->get();

        return view('frontend.index.products-index')->with(compact('featured_products'));
    }
}

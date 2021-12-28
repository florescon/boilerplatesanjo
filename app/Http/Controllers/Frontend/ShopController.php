<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    return view('frontend.shop.index');
	}

    public function show(Product $shop)
    {
    	// $product = Product::whereSlug($product)->firstOrFail();
    	if($shop->parent_id || !$shop->status){
    		abort(404);
    	}

        $related_products = Product::with('line')->whereNull('parent_id')->inRandomOrder()->onlyActive()->limit(4)->get();

        return view('frontend.shop.show', compact('shop', 'related_products'));
    }

    public function datasheet(Product $shop)
    {
    	if($shop->parent_id || !$shop->status){
    		abort(404);
    	}

        return view('frontend.shop.datasheet', compact('shop'));
    }
}

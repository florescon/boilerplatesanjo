<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
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

    	if($shop->parent_id){
    		abort(404);
    	}

        return view('frontend.shop.show', compact('shop'));
    }

}

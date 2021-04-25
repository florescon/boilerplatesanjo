<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    return view('backend.product.index');
	}

    public function create()
    {
        return view('backend.product.create-product');
    }

    public function edit(Product $product)
    {
    	if($product->parent_id){
    		abort(404);
    	}

        return view('backend.product.edit-product')
	        ->withProduct($product);
    }

    public function consumption(Product $product)
    {
        if($product->parent_id){
            abort(404);
        }
        return view('backend.product.consumption-product')
            ->withProduct($product);
    }

    public function destroy(Product $product)
    {
        if($product->id){
            $product_delete = Product::find($product->id);
            $product_delete->delete();
        }
        return redirect()->route('admin.product.index')->withFlashSuccess(__('The product was successfully deleted.'));
    }

	public function deleted()
	{
	    return view('backend.product.deleted');
	}

}

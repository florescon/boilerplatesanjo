<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Exception;

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

    public function list()
    {
        return view('backend.product.list-products');
    }

    public function edit(Product $product)
    {

    	if($product->parent_id){
    		abort(401);
    	}

        return view('backend.product.edit-product', compact('product'));
    }


    public function advanced(Product $product)
    {

        if($product->parent_id){
            abort(404);
        }

        return view('backend.product.advanced-product', compact('product'));
    }


    public function prices(Product $product)
    {

        if($product->parent_id){
            abort(404);
        }

        return view('backend.product.prices-product', compact('product'));
    }


    public function pictures(Product $product)
    {

        if($product->parent_id){
            abort(404);
        }

        return view('backend.product.pictures-product', compact('product'));
    }


    public function moveStock(Product $product)
    {

        if($product->parent_id){
            abort(404);
        }

        return view('backend.product.move-stock-product', compact('product'));
    }


    public function consumption(Product $product)
    {
        if($product->parent_id){
            abort(404);
        }
        return view('backend.product.consumption-product')
            ->withProduct($product);
    }


    public function consumption_filter(Product $product)
    {
        return view('backend.product.consumption-product-filter')
            ->withProduct($product);
    }

    public function createCodes(Product $product)
    {
        $product->load('children');
        $order = 7;

        DB::beginTransaction();

        try {

            foreach ($product->children as $prod) {
                $prod->update(['code' => $product->code.optional($prod->color)->short_name.optional($prod->size)->short_name]);
            }

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating codes.'));
        }

        DB::commit();


        return redirect()->back()->withFlashSuccess(__('Codes updated.'));
    }


    public function destroy(Product $product)
    {
        if($product->id){
            $product->delete();
        }
        return redirect()->route('admin.product.index')->withFlashSuccess(__('The product was successfully deleted.'));
    }

	public function deleted()
	{
	    return view('backend.product.deleted');
	}

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Product::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }

    public function select2LoadMoreGroup(Request $request)
    {
        $search = $request->get('search');
        $data = Product::with('children')->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['products' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }


}

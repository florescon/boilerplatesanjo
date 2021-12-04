<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    return view('backend.size.index');
	}

    public function associates_sub(Size $size)
    {
        $link = route('admin.size.index');
        $associates = $size->products()->paginate(10);
        $model = $size;
        return view('backend.product.associates-subproducts', compact('associates', 'model', 'link'));
    }

    public function associates(Size $size)
    {
        $link = route('admin.size.index');
        $associates = $size->product()->paginate(10);
        $model = $size;
        return view('backend.product.associates-products', compact('associates', 'model', 'link'));
    }

	public function deleted()
	{
	    return view('backend.size.deleted');
	}

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Size::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }

    public function select2LoadMoreFrontend(Request $request)
    {
        $search = $request->get('search');
        $data = Size::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}

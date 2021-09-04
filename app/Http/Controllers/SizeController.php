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

    public function associates(Size $size)
    {
        $associates = $size->products()->paginate(10);
        $model = $size;
        return view('backend.product.associates-products', compact('associates', 'model'));
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

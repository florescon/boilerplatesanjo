<?php

namespace App\Http\Controllers;

use App\Models\Line;
use Illuminate\Http\Request;

class LineController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    return view('backend.line.index');
	}


	public function deleted()
	{
	    return view('backend.line.deleted');
	}

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Line::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }


}

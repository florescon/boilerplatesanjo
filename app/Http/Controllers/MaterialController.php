<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.material.index');
    }

    public function deleted()
    {
        return view('backend.material.deleted');
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Material::with('unit', 'color', 'size')
        ->where('name', 'like', '%' . $search . '%')->orWhere('part_number', 'like', '%' . $search . '%')->orderBy('name')
        ->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }

}

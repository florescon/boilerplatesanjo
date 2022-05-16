<?php

namespace App\Http\Controllers;

use App\Models\Departament;
use Illuminate\Http\Request;

class DepartamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.departament.index');
    }

    public function create()
    {
        return view('backend.departament.create');
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Departament::select(['id', 'name', 'email', 'comment'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}

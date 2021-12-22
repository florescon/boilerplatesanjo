<?php

namespace App\Http\Controllers;

use App\Models\ModelProduct;
use Illuminate\Http\Request;

class ModelProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.model.index');
    }

    public function associates(ModelProduct $model)
    {
        $link = route('admin.model.index');
        $associates = $model->products()->paginate(10);
        $model = $model;
        return view('backend.product.associates-subproducts', compact('associates', 'model', 'link'));
    }

    public function deleted()
    {
        return view('backend.model.deleted');
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = ModelProduct::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}

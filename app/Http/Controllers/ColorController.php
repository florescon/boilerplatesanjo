<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.color.index');
    }

    public function associates_sub(Color $color)
    {
        $link = route('admin.color.index');
        $associates = $color->products()->paginate(10);
        $model = $color;
        return view('backend.product.associates-subproducts', compact('associates', 'model', 'link'));
    }

    public function associates(Color $color)
    {
        // dd('si');
        $link = route('admin.color.index');
        $attribute = $color;
        $nameModel = 'Color';
        $subproduct = true;
        return view('backend.product.associates-subproducts', compact('attribute', 'link', 'nameModel', 'subproduct'));
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Color::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orWhere('short_name', 'like', '%' . $search . '%')->orderBy('name')->paginate(12);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }

    public function select2LoadMoreFrontend(Request $request)
    {
        $search = $request->get('search');
        $data = Color::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(12);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
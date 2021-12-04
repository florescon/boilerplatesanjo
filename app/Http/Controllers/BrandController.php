<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.brand.index');
    }

    public function associates_sub(Brand $brand)
    {
        $link = route('admin.brand.index');
        $associates = $brand->products()->paginate(10);
        $model = $brand;
        return view('backend.product.associates-subproducts', compact('associates', 'model', 'link'));
    }

    public function associates(Brand $brand)
    {
        $link = route('admin.brand.index');
        $associates = $brand->product()->paginate(10);
        $model = $brand;
        return view('backend.product.associates-products', compact('associates', 'model', 'link'));
    }

    public function deleted()
    {
        return view('backend.brand.deleted');
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Brand::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }

    public function select2LoadMoreFrontend(Request $request)
    {
        $search = $request->get('search');
        $data = Brand::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}

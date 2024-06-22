<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.image.index');
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Image::select(['id', 'title', 'image'])->where('title', 'like', '%' . $search . '%')->orderBy('title')->paginate(15);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}

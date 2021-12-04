<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.document.index');
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Document::select(['id', 'title', 'comment'])->where('title', 'like', '%' . $search . '%')->orderBy('title')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}

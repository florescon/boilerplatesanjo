<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.thread.index');
    }

    public function deleted()
    {
        return view('backend.thread.deleted');
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Thread::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orWhere('code', 'like', '%' . $search . '%')->orderBy('name')->paginate(12);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }

}

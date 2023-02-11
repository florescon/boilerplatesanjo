<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = ServiceType::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(10);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = City::select(['id', 'city', 'city_ascii', 'lat', 'lng', 'country'])->where('name', 'like', '%' . $search . '%')->orWhere('short_name', 'like', '%' . $search . '%')->orderBy('name')->paginate(12);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}

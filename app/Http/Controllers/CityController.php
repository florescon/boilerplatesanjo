<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = City::select(['id', 'city', 'city_ascii', 'lat', 'lng', 'country', 'capital'])->where('city', 'like', '%' . $search . '%')->orWhere('country', 'like', '%' . $search . '%')->orWhere('capital', 'like', '%' . $search . '%')->orderBy('city')->paginate(12);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}

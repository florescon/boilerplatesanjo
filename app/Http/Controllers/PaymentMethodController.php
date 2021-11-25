<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = PaymentMethod::select(['id', 'title', 'is_enabled'])->where('title', 'like', '%' . $search . '%')->orderBy('title')->paginate(8);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}

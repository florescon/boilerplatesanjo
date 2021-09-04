<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Product;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products_count = Product::where('parent_id', '<>', NULL)
            ->whereHas('parent', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->count();

        return view('frontend.index_ga')->with(compact('products_count'));
    }

}

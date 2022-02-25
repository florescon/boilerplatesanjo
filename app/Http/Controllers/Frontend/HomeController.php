<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Product;
use App\Models\Frontend\Image;

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

        $logos = Image::whereType('2')->orderBy('sort')->get();        

        $banner = Image::whereType('1')->whereBetween('sort', [1, 8])->get();

        $one = $banner->where('sort', '1')->first() ?? null;
        $two = $banner->where('sort','2')->first() ?? null;
        $three = $banner->where('sort','3')->first() ?? null;
        $four = $banner->where('sort', '4')->first() ?? null;
        $five = $banner->where('sort','5')->first() ?? null;
        $six = $banner->where('sort','6')->first() ?? null;
        $seven = $banner->where('sort','7')->first() ?? null;
        $eight = $banner->where('sort','8')->first() ?? null;

        return view('frontend.index_ga')->with(compact('products_count', 'logos', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight'));
    }
}

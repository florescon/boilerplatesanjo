<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Frontend\Image;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Image::whereType('3')->whereBetween('sort', [1, 5])->get();

        $one = $gallery->where('sort', '1')->first() ?? null;
        $two = $gallery->where('sort','2')->first() ?? null;
        $three = $gallery->where('sort','3')->first() ?? null;
        $four = $gallery->where('sort', '4')->first() ?? null;
        $five = $gallery->where('sort','5')->first() ?? null;

        return view('frontend.pages.gallery')->with(compact('one', 'two', 'three', 'four', 'five'));
    }
}

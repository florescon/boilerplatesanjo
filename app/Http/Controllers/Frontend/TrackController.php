<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class TrackController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('track');
    }

    public function discount()
    {
        return URL::temporarySignedRoute(
            'discountCode', now()->addMinutes(30)
        );
    }

}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Domains\Auth\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use App\Models\Order;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('backend.dashboard');
    }

    public function index_old()
    {
        return view('backend.dashboard_old');
    }

}
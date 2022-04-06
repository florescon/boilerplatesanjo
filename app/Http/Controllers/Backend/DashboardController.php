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
        // $from = session()->has('from') ? session('from') : (new Carbon('first day of January ' . date('Y')))->toDateTimeString();
        $from = session()->has('from') ? session('from') : (now()->subMonths(12))->toDateTimeString();
        $to = session()->has('to') ? session('to') : now()->toDateTimeString();

        $period = CarbonPeriod::create($from, $to);

        $range = [];
        $dbData = [];
        $months = [];
        foreach($period as $date){
            $range[$date->format('Y-m')] = 0;
            $months[$date->format('Y-m')] = $date->formatLocalized('%Y-%m');
        }

        // $month = ['01','02','03','04','05','06', '07', '08', '09', '10', '11', '12'];

        // $lastMonth =  $date->subYear()->format('m'); // 11

        // $user = [];

        foreach ($months as $key => $value) {
            // dd($value);

            $months2[] = $key;
            $user[] = User::where(\DB::raw('DATE_FORMAT(created_at, \'%Y-%m\')'), $value)->count();
        }

        $orders = Order::with('product_sale', 'product_order', 'user', 'last_status_order.status')->orderByDesc('created_at')->simplePaginate(10);

        // dd($months2);

        // dd($user);

        return view('backend.dashboard')->with('months2',json_encode($months2,JSON_NUMERIC_CHECK))->with('user',json_encode($user,JSON_NUMERIC_CHECK))->with(compact('orders'));
    }
}
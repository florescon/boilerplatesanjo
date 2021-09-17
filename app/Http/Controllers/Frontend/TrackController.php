<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Frontend\Order;
use App\Http\Requests\Frontend\Order\SearchRequest;
use Carbon\Carbon;


class TrackController extends Controller
{

    public function orderTrack()
    {
        return view('frontend.track.index');
    }

    public function search(SearchRequest $request){

        $search = $request->search;
        return redirect()->route('frontend.track.show', $request->slug);
    }

    public function show(Order $order)
    {

        $site = \App\Models\Setting::first()->days_orders ?? 30;

        $limit = $order->created_at->addDays($site);
        $now = Carbon::now();
        $result = $now->gt($limit);
        $order_id = $order->id;
        $tracking_number = $order->slug;

        $percentage_status = $order->last_status_order->status->percentage ?? '';

        return view('frontend.track.show')->with(compact('result', 'order_id', 'tracking_number', 'limit', 'order', 'percentage_status'));
    }
}

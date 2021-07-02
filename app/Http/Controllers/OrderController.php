<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use App\Models\StatusOrder;
use Illuminate\Http\Request;
use PDF;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.order.index');
    }


    public function edit(Order $order)
    {

        return view('backend.order.edit-order', compact('order'));
    }

    public function print(Order $order)
    {
        return view('backend.order.print-order', compact('order'));
    }

    public function ticket(Order $order)
    {

        $pdf = PDF::loadView('backend.order.ticket-order',compact('order'))->setPaper([0, 0, 1385.98, 296.85], 'landscape');
    
        // ->setPaper('A8', 'portrait')

        return $pdf->stream();

        // return view('backend.order.ticket-order');
    }

    public function advanced(Order $order)
    {

        return view('backend.order.advanced-order', compact('order'));
    }


    public function records(Order $order)
    {

        if($order->parent_order_id == true){
            abort(401);
        }

        $records = $order->status_order()->orderBy('created_at', 'desc')->paginate('10')->fragment('main');
        return view('backend.order.records-status-order', compact('order', 'records'));
    }

    public function where_is_products(Order $order)
    {

        return view('backend.order.where-is-products')
            ->withOrder($order);
    }


    public function suborders(Order $order)
    {

        return view('backend.order.suborders')
            ->withOrder($order);
    }

    public function assignments(Order $order, Status $status)
    {
        
        if($status->to_add_users == false){
            abort(401);
        }

        // dd($order->id);
        return view('backend.order.assignments-order', compact('order', 'status'));
    }

    public function deleted()
    {
        return view('backend.order.deleted');
    }

    public function destroy(Order $order)
    {
        if($order->id){
            $order->delete();
        }
        return redirect()->route('admin.order.index')->withFlashSuccess(__('The order/sale was successfully deleted.'));
    }


}

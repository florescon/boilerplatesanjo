<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use App\Models\StatusOrder;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;
use Carbon\Carbon;
use App\Events\Order\OrderDeleted;

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
    public function suborders_list()
    {
        return view('backend.order.suborders_list');
    }
    public function sales_list()
    {
        return view('backend.order.sales_list');
    }
    public function mix_list()
    {
        return view('backend.order.mix_list');
    }
    public function all_list()
    {
        return view('backend.order.all_list');
    }

    public function edit(Order $order)
    {
        $vvar =  $order->created_at->timestamp;

        return view('backend.order.edit-order', compact('order', 'vvar'));
    }

    public function print(Order $order)
    {
        return view('backend.order.print-order', compact('order'));
    }

    public function ticket(Order $order)
    {
        $pdf = PDF::loadView('backend.order.ticket-suborder',compact('order'))->setPaper([0, 0, 1385.98, 296.85], 'landscape');
        // ->setPaper('A8', 'portrait')

        return $pdf->stream();
        // return view('backend.order.ticket-order');
    }

    public function ticket_order(Order $order)
    {
        $pdf = PDF::loadView('backend.order.ticket-order',compact('order'))->setPaper([0, 0, 1385.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function ticket_assignment(Order $order, Ticket $ticket)
    {
        $pdf = PDF::loadView('backend.order.ticket-assignment',compact('ticket'))->setPaper([0, 0, 1385.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function ticket_materia(Order $order)
    {
        $order->load(['materials_order' => function($query){
                    $query->groupBy('material_id')->selectRaw('*, sum(quantity) as sum');
                }]
        );
        $pdf = PDF::loadView('backend.order.ticket-materia',compact('order'))->setPaper([0, 0, 1385.98, 296.85], 'landscape');

        return $pdf->stream();
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

    public function records_delivery(Order $order)
    {
        $records_delivery = $order->orders_delivery()->orderBy('created_at', 'desc')->paginate('10')->fragment('delivery');
        return view('backend.order.records-delivery-order', compact('order', 'records_delivery'));
    }

    public function records_payment(Order $order)
    {
        $records_payment = $order->orders_payments()->orderBy('created_at', 'desc')->paginate('10')->fragment('payment');
        return view('backend.order.records-payment-order', compact('order', 'records_payment'));
    }

    public function where_is_products(Order $order)
    {
        return view('backend.order.where-is-products')
            ->withOrder($order);
    }

    public function suborders(Order $order)
    {
        if(!$order->exist_user_departament){
            abort(401);
        }

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

        event(new OrderDeleted($order));

        return redirect()->route('admin.order.index')->withFlashSuccess(__('The order/sale was successfully deleted'));
    }
}
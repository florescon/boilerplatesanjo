<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

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

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml('hello world');

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
    }

    public function advanced(Order $order)
    {

        return view('backend.order.advanced-order', compact('order'));
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

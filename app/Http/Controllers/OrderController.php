<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use App\Models\StatusOrder;
use App\Models\MaterialOrder;
use App\Models\ServiceOrder;
use App\Models\Ticket;
use App\Models\Station;
use App\Models\Batch;
use App\Models\ProductOrder;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;
use DB;
use Carbon\Carbon;
use App\Events\Order\OrderDeleted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

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
    public function quotations_list()
    {
        return view('backend.order.quotations_list');
    }
    public function all_list()
    {
        return view('backend.order.all_list');
    }

    public function all_list_store()
    {
        return view('backend.store.all_list_store');
    }

    public function orders_list_store()
    {
        return view('backend.store.orders_list_store');
    }

    public function sales_list_store()
    {
        return view('backend.store.sales_list_store');
    }

    public function output_products_list_store()
    {
        return view('backend.store.output_products_list_store');
    }

    public function requests_list_store()
    {
        return view('backend.store.requests_list_store');
    }

    public function quotations_list_store()
    {
        return view('backend.store.quotations_list_store');
    }

    public function mix_list_store()
    {
        return view('backend.store.mix_list_store');
    }

    public function edit(Order $order)
    {
        if(($order->branch_id == 0) && $order->flowchart){
            return redirect()->route('admin.order.edit_chart', $order->id);
        }

        $vvar =  $order->created_at->timestamp;

        return view('backend.order.edit-order', compact('order', 'vvar'));
    }

    public function edit_store(Order $order)
    {
        if($order->from_store){

            $vvar =  $order->created_at->timestamp;

            return view('backend.order.edit-order', compact('order', 'vvar'));
        }
        else{
            abort(401);
        }
    }

    public function edit_chart(Order $order)
    {
        if(($order->branch_id == 0) && !$order->flowchart){
            return redirect()->route('admin.order.edit', $order->id);
        }

        $vvar =  $order->created_at->timestamp;

        return view('backend.chart.order.edit', compact('order', 'vvar'));
    }

    public function createsuborder()
    {
        return view('backend.order.create-suborder');
    }

    public function printexportorders(?string $orders = null)
    {
        return view('backend.order.print-export-orders-index', ['orders' => $orders]);
    }

    public function printexportbydate(?string $dateInput = null, ?string $dateOutput = null, bool $summary = false, bool $isProduct = false, bool $isService = false)
    {
        return view('backend.order.print-export-by-date-index', ['dateInput' => $dateInput, 'dateOutput' => $dateOutput, 'summary' => $summary, 'isProduct' => $isProduct, 'isService' => $isService]);
    }

    public function report(Order $order)
    {
        return view('backend.order.report', ['order' => $order]);
    }

    public function newformat(Order $order)
    {
        return view('backend.order.print-newformat', ['order' => $order]);
    }

    public function print(Order $order, bool $breakdown = false, bool $grouped = false, $emptyPrices = false)
    {
        // $selectSub = DB::table('products')->join('products', 'products.id', '=', 'products.parent_id')->whereRaw('product_order.product_id = product.id');

        $orderServices = DB::table('product_order as a')
                ->selectRaw('
                    b.name as product_name,
                    b.code as product_code,
                    b.color_id as color_name,
                    b.size_id as size_name,
                    b.brand_id as brand_name,
                    min(a.price) as min_price,
                    max(a.price) as max_price,
                    min(a.price) <> max(a.price) as omg,
                    sum(a.quantity) as sum,
                    sum(a.quantity * a.price) as sum_total,
                    a.quantity as total_by_product
                ')
                ->join('products as b', 'a.product_id', '=', 'b.id')
                ->where('order_id', $order->id)
                ->where('b.type', '=', 0)
                ->groupBy('b.id', 'a.price')
                ;

        // dd($orderServices);

        $orderGroup = DB::table('product_order as a')
            ->selectRaw('
                c.name as product_name,
                c.code as product_code,
                d.name as color_name,
                e.name as size_name,
                f.name as brand_name,
                min(a.price) as min_price,
                max(a.price) as max_price,
                min(a.price) <> max(a.price) as omg,
                sum(a.quantity) as sum,
                sum(a.quantity * a.price) as sum_total,
                count(*) as total_by_product
            ')
            ->join('products as b', 'a.product_id', '=', 'b.id')
            ->join('products as c', 'b.parent_id', '=', 'c.id')
            ->join('colors as d', 'b.color_id', '=', 'd.id')
            ->join('sizes as e', 'b.size_id', '=', 'e.id')
            ->join('brands as f', 'c.brand_id', '=', 'f.id')  // Agregamos el join con la tabla brands
            ->groupBy('b.parent_id', 'b.color_id', 'a.price')
            ->where('order_id', $order->id)
            ->orderBy('product_name')
            ->orderBy('color_name')
            ->union($orderServices)
            ->get();

        // dd($orderGroup);

        return view('backend.order.print-order', compact('order', 'breakdown', 'orderGroup', 'grouped', 'emptyPrices'));
    }

    public function printgropedwithoutprice(Order $order)
    {
        // $selectSub = DB::table('products')->join('products', 'products.id', '=', 'products.parent_id')->whereRaw('product_order.product_id = product.id');

        $orderServices = DB::table('product_order as a')
                ->selectRaw('
                    b.name as product_name,
                    b.code as product_code,
                    b.color_id as color_name,
                    b.size_id as size_name,
                    b.brand_id as brand_name,
                    min(a.price) as min_price,
                    max(a.price) as max_price,
                    min(a.price) <> max(a.price) as omg,
                    sum(a.quantity) as sum,
                    sum(a.quantity * a.price) as sum_total,
                    a.quantity as total_by_product
                ')
                ->join('products as b', 'a.product_id', '=', 'b.id')
                ->where('order_id', $order->id)
                ->where('b.type', '=', 0)
                ->groupBy('b.id', 'a.price')
                ;

        // dd($orderServices);

        $orderGroup = DB::table('product_order as a')
            ->selectRaw('
                c.name as product_name,
                c.code as product_code,
                d.name as color_name,
                e.name as size_name,
                f.name as brand_name,
                min(a.price) as min_price,
                max(a.price) as max_price,
                min(a.price) <> max(a.price) as omg,
                sum(a.quantity) as sum,
                sum(a.quantity * a.price) as sum_total,
                count(*) as total_by_product
            ')
            ->join('products as b', 'a.product_id', '=', 'b.id')
            ->join('products as c', 'b.parent_id', '=', 'c.id')
            ->join('colors as d', 'b.color_id', '=', 'd.id')
            ->join('sizes as e', 'b.size_id', '=', 'e.id')
            ->join('brands as f', 'c.brand_id', '=', 'f.id')  // Agregamos el join con la tabla brands
            ->groupBy('b.parent_id', 'b.color_id', 'a.price')
            ->where('order_id', $order->id)
            ->orderBy('product_name')
            ->orderBy('color_name')
            ->union($orderServices)
            ->get();

        // dd($orderGroup);

        return view('backend.order.print-order-grouped-without-prices', compact('order', 'orderGroup'));
    }
    public function ticket(Order $order)
    {
        $pdf = PDF::loadView('backend.order.ticket-suborder',compact('order'))->setPaper([0, 0, 2000.98, 296.85], 'landscape');
        // ->setPaper('A8', 'portrait')

        return $pdf->stream();
        // return view('backend.order.ticket-order');
    }

    public function ticket_order(Order $order, bool $breakdown = false, $emptyPrices = false)
    {
        $pdf = PDF::loadView('backend.order.ticket-order',compact('order', 'breakdown', 'emptyPrices'))->setPaper([0, 0, 2385.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function ticket_monitoring(Order $order)
    {
        $pdf = PDF::loadView('backend.order.ticket-monitoring',compact('order'))->setPaper([0, 0, 500.98, 296.85], 'landscape');

        return $pdf->stream();
    }


    public function ticket_store(Order $order)
    {
        if($order->from_store){
            $pdf = PDF::loadView('backend.order.ticket-suborder',compact('order'))->setPaper([0, 0, 2000.98, 296.85], 'landscape');

            return $pdf->stream();
        }
        else{
            abort(401);
        }
    }

    public function ticket_order_store(Order $order, bool $breakdown = false, bool $emptyPrices = false)
    {
        if($order->from_store){
            $pdf = PDF::loadView('backend.order.ticket-order',compact('order', 'breakdown', 'emptyPrices'))->setPaper([0, 0, 2385.98, 296.85], 'landscape');

            return $pdf->stream();
        }
        else{
            abort(401);
        }
    }

    public function print_store(Order $order, bool $breakdown = false, bool $grouped = false, $emptyPrices = false)
    {
        if($order->from_store){

            // $selectSub = DB::table('products')->join('products', 'products.id', '=', 'products.parent_id')->whereRaw('product_order.product_id = product.id');

        $orderServices = DB::table('product_order as a')
                ->selectRaw('
                    b.name as product_name,
                    b.code as product_code,
                    b.color_id as color_name,
                    b.brand_id as brand_name,
                    b.size_id as size_name,
                    min(a.price) as min_price,
                    max(a.price) as max_price,
                    min(a.price) <> max(a.price) as omg,
                    sum(a.quantity) as sum,
                    sum(a.quantity * a.price) as sum_total,
                    a.quantity as total_by_product
                ')
                ->join('products as b', 'a.product_id', '=', 'b.id')
                ->where('order_id', $order->id)
                ->where('b.type', '=', 0)
                ->groupBy('b.id', 'a.price')
                ;

        // dd($orderServices);

        $orderGroup = DB::table('product_order as a')
            ->selectRaw('
                c.name as product_name,
                c.code as product_code,
                d.name as color_name,
                e.name as size_name,
                f.name as brand_name,
                min(a.price) as min_price,
                max(a.price) as max_price,
                min(a.price) <> max(a.price) as omg,
                sum(a.quantity) as sum,
                sum(a.quantity * a.price) as sum_total,
                count(*) as total_by_product
            ')
            ->join('products as b', 'a.product_id', '=', 'b.id')
            ->join('products as c', 'b.parent_id', '=', 'c.id')
            ->join('colors as d', 'b.color_id', '=', 'd.id')
            ->join('sizes as e', 'b.size_id', '=', 'e.id')
            ->join('brands as f', 'c.brand_id', '=', 'f.id')  // Agregamos el join con la tabla brands
            ->groupBy('b.parent_id', 'b.color_id', 'a.price')
            ->where('order_id', $order->id)
            ->orderBy('product_name')
            ->orderBy('color_name')
            ->union($orderServices)
            ->get();

        // dd($orderGroup);
            return view('backend.order.print-order', compact('order', 'breakdown', 'orderGroup', 'grouped', 'emptyPrices'));

        }
        else{
            abort(401);
        }
    }

    public function ticket_assignment(Order $order, Ticket $ticket)
    {
        $pdf = PDF::loadView('backend.order.ticket-assignment',compact('order', 'ticket'))->setPaper([0, -16, 2085.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function ticket_batch(Order $order, Batch $batch)
    {
        $pdf = PDF::loadView('backend.order.ticket-batch',compact('order', 'batch'))->setPaper([0, -16, 2085.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function print_service_order(Order $order, ServiceOrder $service)
    {
        $pdf = PDF::loadView('backend.serviceorder.print-service-order',compact('order', 'service'))->setPaper([0, -16, 800, 630], 'landscape');

        return $pdf->stream();

        // return view('backend.serviceorder.print-service-order', compact('order', 'service'));
    }


    public function print_service_order_html(Order $order, ServiceOrder $service)
    {
        return view('backend.serviceorder.print-service-order-html', compact('order', 'service'));
    }

    public function ticket_materia(Order $order)
    {
        $order->load(['materials_order' => function($query){
                    $query->groupBy('material_id')->selectRaw('*, sum(quantity) as sum');
                }]
        );

        $visibleOrder = true;

        $pdf = PDF::loadView('backend.order.ticket-materia',compact('order', 'visibleOrder'))->setPaper([0, 0, 4385.98, 296.85], 'landscape');

        return $pdf->stream();
    }


    public function ticket_materia_station(Order $order, Station $station)
    {
        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();

            $ordercollection->push([
                'id' => $order->id,
                'folio' => $order->folio,
                'user' => optional($order->user)->name,
                'type' => $order->characters_type_order,
                'comment' => $order->comment,
            ]);

            foreach($station->product_station as $product_statione){
                $quantity = $product_statione->quantity;

                if($product_statione->product_order->gettAllConsumptionSecond($quantity) != 'empty'){
                    foreach($product_statione->product_order->gettAllConsumptionSecond($quantity) as $key => $consumption){
                        $consumptionCollect->push([
                            'order' => $order->id,
                            'product_order_id' => $product_statione->product_order->id, 
                            'material_name' => $consumption['material'],
                            'part_number' => $consumption['part_number'],
                            'material_id' => $key,
                            'unit' => $consumption['unit'],
                            'unit_measurement' => $consumption['unit_measurement'],
                            'vendor' => $consumption['vendor'],
                            'family' => $consumption['family'],
                            'quantity' => $consumption['quantity'],
                            'stock' => $consumption['stock'],
                        ]);
                    }
                }
            }

        $materials = $consumptionCollect->groupBy('material_id')->map(function ($row) {
                    return [
                        'order' => $row[0]['order'],
                        'product_order_id' => $row[0]['product_order_id'], 
                        'material_name' => $row[0]['material_name'],
                        'part_number' => $row[0]['part_number'],
                        'material_id' => $row[0]['material_id'],
                        'unit' => $row[0]['unit'],
                        'unit_measurement' => $row[0]['unit_measurement'],
                        'vendor' => $row[0]['vendor'],
                        'family' => $row[0]['family'],
                        'quantity' => $row->sum('quantity'),
                        'stock' => $row[0]['stock'],
                    ];
                });


        $allMaterials = $materials->map(function ($product) {
        return [
            'order'            => $product['order'],
            'material_name' => $product['material_name'],
            'part_number'         => $product['part_number'],
            'unit_measurement' => $product['unit_measurement'],
            'quantity' => $product['quantity'],
            ];
        });

        $pdf = PDF::loadView('backend.order.ticket-materia-station',compact('order', 'station', 'allMaterials'))->setPaper([0, 0, 1585.98, 296.85], 'landscape');

        return $pdf->stream();
    }


    public function ticket_materia_prod(Order $order, ProductionBatch $station)
    {
        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();

            $ordercollection->push([
                'id' => $order->id,
                'folio' => $order->folio,
                'user' => optional($order->user)->name,
                'type' => $order->characters_type_order,
                'comment' => $order->comment,
            ]);

            foreach($station->items as $product_statione){

                $productOrder = ProductOrder::where('product_id', $product_statione->product_id)->where('order_id', $station->order_id)->first();

                $quantity = $product_statione->input_quantity;

                if($productOrder->gettAllConsumptionSecond($quantity) != 'empty'){
                    foreach($productOrder->gettAllConsumptionSecond($quantity) as $key => $consumption){
                        $consumptionCollect->push([
                            'order' => $order->id,
                            'product_order_id' => $productOrder->id, 
                            'material_name' => $consumption['material'],
                            'part_number' => $consumption['part_number'],
                            'material_id' => $key,
                            'unit' => $consumption['unit'],
                            'unit_measurement' => $consumption['unit_measurement'],
                            'vendor' => $consumption['vendor'],
                            'family' => $consumption['family'],
                            'quantity' => $consumption['quantity'],
                            'stock' => $consumption['stock'],
                        ]);
                    }
                }
            }

        $materials = $consumptionCollect->groupBy('material_id')->map(function ($row) {
            return [
                'order' => $row[0]['order'],
                'product_order_id' => $row[0]['product_order_id'], 
                'material_name' => $row[0]['material_name'],
                'part_number' => $row[0]['part_number'],
                'material_id' => $row[0]['material_id'],
                'unit' => $row[0]['unit'],
                'unit_measurement' => $row[0]['unit_measurement'],
                'vendor' => $row[0]['vendor'],
                'family' => $row[0]['family'],
                'quantity' => $row->sum('quantity'),
                'stock' => $row[0]['stock'],
            ];
        });


        $allMaterials = $materials->map(function ($product) {
            return [
                'order'            => $product['order'],
                'material_name' => $product['material_name'],
                'part_number'         => $product['part_number'],
                'unit_measurement' => $product['unit_measurement'],
                'quantity' => $product['quantity'],
                ];
        });

        $pdf = PDF::loadView('backend.order.ticket-materia-prod',compact('order', 'station', 'allMaterials'))->setPaper([0, 0, 1585.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function ticket_prod(Order $order, ProductionBatch $station)
    {
        $pdf = PDF::loadView('backend.order.ticket-prod',compact('order', 'station'))->setPaper([0, 0, 1585.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function checklist_prod(Order $order, ProductionBatch $station)
    {
        $station->load('material_order.material');

        
        $groupedMaterials = $station->material_order->groupBy('material_id')->map(function ($group) {
            return [
                'order_id' => $group[0]->order_id,
                'material' => $group[0]->material->full_name,
                'price' => $group[0]->price,
                'unit_quantity' => $group[0]->unit_quantity,
                'sum' => $group->sum('quantity').' '.$group[0]->material->unit_name_label,
            ];
        });

        // dd($groupedMaterials);

        $pdf = PDF::loadView('backend.station.checklist-ticket-prod',compact('station', 'groupedMaterials'))->setPaper([0, -16, 2085.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function short_ticket_materia(Order $order)
    {
        $order->load(['materials_order' => function($query){
                    $query->groupBy('material_id')->selectRaw('*, sum(quantity) as sum');
                }]
        );

        $visibleOrder = false;

        $pdf = PDF::loadView('backend.order.ticket-materia',compact('order', 'visibleOrder'))->setPaper([0, 0, 1385.98, 890.85], 'landscape');

        return $pdf->stream();
    }

    public function advanced(Order $order)
    {
        $limit = $order->created_at->addDays(7);
        $now = Carbon::now();
        $result = $now->gt($limit);

        return view('backend.order.advanced-order', compact('order', 'result'));
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

    public function service_orders(Order $order)
    {
        $products = $order->products()->orderBy('created_at', 'desc')->paginate('10');

        $service_orders = $order->service_orders()->orderBy('created_at', 'desc')->paginate('10');
        
        return view('backend.order.service_orders', [
            'products' => $products,
            'service_orders' => $service_orders,
            'order' => $order,
        ]);
    }

    public function children_orders(Order $order)
    {
        $products = $order->products()->orderBy('created_at', 'desc')->paginate('10');

        $service_orders = $order->order_children()->orderBy('created_at', 'desc')->paginate('10');
        
        return view('backend.order.children_orders', [
            'products' => $products,
            'service_orders' => $service_orders,
            'order' => $order,
        ]);
    }

    public function records_payment(Order $order)
    {
        $records_payment = $order->orders_payments()->orderBy('created_at', 'desc')->paginate('10')->fragment('payment');
        return view('backend.order.records-payment-order', compact('order', 'records_payment'));
    }

    public function records_payment_store(Order $order)
    {
        if($order->from_store){
            $records_payment = $order->orders_payments()->orderBy('created_at', 'desc')->paginate('10')->fragment('payment');
            return view('backend.order.records-payment-order', compact('order', 'records_payment'));
        }
        else{
            abort(401);
        }
    }

    public function where_is_products(Order $order)
    {
        return view('backend.order.where-is-products')
            ->withOrder($order);
    }

    public function end_add_stock(Order $order)
    {
        return redirect()->route('admin.order.advanced', $order->id)->withFlashSuccess(__('The order/sale was successfully deleted'));
    }

    public function delete_consumption(Order $order)
    {
        $limit = $order->created_at->addDays(7);
        $now = Carbon::now();
        $result = $now->gt($limit);

        if(!$result){
            $order->update([
                'feedstock_changed_at' => now()
            ]);

            MaterialOrder::where('order_id', $order->id)->get()->each->delete();

            // $order->materials_order()->delete();
        }

        return redirect()->route('admin.order.advanced', $order->id)->withFlashSuccess(__('The feedstock was successfully deleted'));
    }

    public function reasign_user_departament(Order $order)
    {
        $limit = $order->created_at->addDays(7);
        $now = Carbon::now();
        $result = $now->gt($limit);

        if(!$result){
            $order->update([
                'user_departament_changed_at' => now()
            ]);
        }

        return redirect()->route('admin.order.advanced', $order->id)->withFlashSuccess(__('The user/departament was successfully reasigned'));
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

        return view('backend.order.assignments-order', compact('order', 'status'));
    }

    public function batches(Order $order, Status $status)
    {
        if($status->batch == false){
            abort(401);
        }

        return view('backend.order.batches-order', compact('order', 'status'));
    }

    public function station(Order $order, Status $status)
    {
        return view('backend.order.station-order', compact('order', 'status'));
    }

    public function work(Order $order, Status $status)
    {
        return view('backend.order.work-order', compact('order', 'status'));
    }

    public function production_batch(Order $order, ProductionBatch $productionBatch)
    {
        return view('backend.order.production-batch',
        [
            'productionBatch' => $productionBatch,
        ]
    );
    }

    public function process(Order $order, Status $status)
    {
        if($status->process == false){
            abort(401);
        }

        return view('backend.order.process-order', compact('order', 'status'));
    }

    public function quotations_chart_list()
    {
        return view('backend.chart.quotations');
    }
    public function all_chart()
    {
        return view('backend.chart.all_chart');
    }

    public function flowchart_request()
    {
        return view('backend.flowchart.requests');
    }

    public function flowchart_request_work()
    {
        return view('backend.flowchart.requests_work');
    }

    public function chart()
    {
        $lastProcessId = Order::getLastProcess()->id;

        $orders = Order::with([
            'product_order.product_station_received',
            'product_order.product_station_out',
            'user',
            'productionBatches',
            'products',
            'last_status_order.status',
        ])        
            // ->onlyAssignment(6)
        ->doesntHave('stations') // <- Solo órdenes con al menos una estación

        ->where(function($q) {
                        $q->whereRaw("(
                            SELECT COALESCE(SUM(pbi.input_quantity), 0) 
                            FROM production_batch_items pbi
                            JOIN production_batches pb ON pb.id = pbi.batch_id
                            WHERE pb.order_id = orders.id
                            AND pbi.is_principal = 1
                            AND pbi.with_previous IS NULL
                        ) != (
                            SELECT COALESCE(SUM(po.quantity), 0)
                            FROM product_order po
                            JOIN products p ON p.id = po.product_id
                            WHERE po.order_id = orders.id
                            AND p.type = 1
                        )")
                        ->orWhereRaw("EXISTS (
                            SELECT 1 
                            FROM production_batch_items pbi
                            JOIN production_batches pb ON pb.id = pbi.batch_id
                            WHERE pb.order_id = orders.id
                            AND pbi.active != 0
                        )");
                    })                    
        ->onlyOrders()
        ->outFromStore()
        ->flowchart()
        ->orderBy('id', 'desc')
        ->paginate(3);


        return view('backend.information.chart', compact('orders'));
    }

    public function deleted()
    {
        return view('backend.order.deleted');
    }

    public function deleted_work()
    {
        return view('backend.order.deleted_work');
    }

    public function destroy(Order $order)
    {
        if($order->stations()->exists()){
            abort(403, __('Tiene datos asociados') . ' :(');
        }

        if($order->id){
            $order->delete();
        }

        event(new OrderDeleted($order));

        return redirect()->route($order->from_store ? 'admin.store.all.index' : 'admin.order.index')->withFlashSuccess(__('The order/sale was successfully deleted'));
    }

    public function runDeleteOldOrders()
    {
        Artisan::call('orders:delete-old');

        return redirect()->back()->withFlashSuccess('El comando se ejecutó correctamente.');
    }

}

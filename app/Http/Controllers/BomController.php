<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder;
use Excel;
use App\Exports\BillOfMaterialsExport;
use App\Exports\ProductsBomExport;
use App\Models\Order;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use DB;

class BomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.bom.index');
    }

    public function ticket_bom($selectedtypes = null)
    {
        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();

        $orders = Order::whereIn('id', json_decode($selectedtypes))->with('products.consumption_filter.material', 'products.parent', 'products.order.user.customer')->get();


        foreach($orders as $order){

            $ordercollection->push([
                'id' => $order->id,
                'folio' => $order->folio,
                'user' => optional($order->user)->name,
                'type' => $order->characters_type_order,
                'comment' => $order->comment,
            ]);

            foreach($order->products as $product_order){

                $productsCollection->push([
                    'productId' => $product_order->id,
                    'productParentId' => $product_order->product->parent_id ?? $product_order->product_id,
                    'productParentName' => $product_order->product->only_name ?? null,
                    'productParentCode' => $product_order->product->parent_code ?? null,
                    'productOrder' => $product_order->order->folio_or_id_clear,
                    'productName' => $product_order->product->full_name_clear ?? null,
                    'productColor' => $product_order->product->color_id,
                    'productColorName' => $product_order->product->color->name ?? '',
                    'productQuantity' => $product_order->quantity,
                    'isService' => !$product_order->product->parent_id ? true : false,
                    'customer' => $product_order->order->user_name ?? null,
                ]);

                if($product_order->gettAllConsumption() != 'empty'){
                    foreach($product_order->gettAllConsumption() as $key => $consumption){
                        $consumptionCollect->push([
                            'order' => $order->id,
                            'product_order_id' => $product_order->id, 
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
        }

        $products = $productsCollection->groupBy(['productParentId', function ($item) {
            return $item['productColor'];
        }], $preserveKeys = false);

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


        $ordercollection->toArray();

        $allMaterials = $materials->map(function ($product) {
        return [
            'order'            => $product['order'],
            'material_name' => $product['material_name'],
            'part_number'         => $product['part_number'],
            'unit_measurement' => $product['unit_measurement'],
            'quantity' => $product['quantity'],
            'family' => $product['family']
            ];
        })->sortBy(['family', 'asc'],['material_name', 'asc']);



        // dd($allMaterials);


        $pdf = PDF::loadView('backend.bom.ticket-bom', ['ordercollection' => $ordercollection, 'materials' => $allMaterials])->setPaper([0, -16, 2085.98, 296.85], 'landscape');

        return $pdf->stream();

    }
}

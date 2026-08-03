<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Order;
use App\Models\BatchProduct;
use Illuminate\Http\Request;
use DB;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.batch.index');
    }

    public function edit(Batch $batch)
    {
        return view('backend.batch.edit-batch', compact('batch'));
    }

    public function report(Batch $batch)
    {

    $batchOne = $batch;

    $order = Order::findOrFail($batchOne->order_id);

    $sizes = $batchOne->getProductSizesFromBatch();
    $colors = $batchOne->getProductColorsFromBatch();


    // Solo batch_items
    $batchItems = DB::table('batch_items')
        ->join('products', 'batch_items.product_id', '=', 'products.id')
        ->leftJoin('products as parents', 'products.parent_id', '=', 'parents.id')
        ->where('batch_items.batch_id', $batch->id)
        ->select(
            'batch_items.id',
            'batch_items.product_id',
            'batch_items.quantity as qty',

            'products.parent_id',
            'products.size_id',
            'products.color_id',

            'parents.name as parent_name',
            'parents.code as parent_code'
        )
        ->get();



    $matrix = [];
    $parents = [];
    $totals = [];


    foreach ($batchItems as $item) {

        $parentId = $item->parent_id ?: 0;


        // Lista de productos padre
        $parents[$parentId] = $item->parent_name
            ? $item->parent_code . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $item->parent_name
            : 'Sin producto padre';



        // MATRIZ:
        // parent -> color -> size
        $matrix[$parentId][$item->color_id][$item->size_id] = $item;



        // Totales
        $qty = $item->qty ?? 0;


        // total por color
        $totals[$parentId]['colors'][$item->color_id] =
            ($totals[$parentId]['colors'][$item->color_id] ?? 0) + $qty;


        // total por talla
        $totals[$parentId]['sizes'][$item->size_id] =
            ($totals[$parentId]['sizes'][$item->size_id] ?? 0) + $qty;


        // total producto padre
        $totals[$parentId]['product'] =
            ($totals[$parentId]['product'] ?? 0) + $qty;
    }


    $sizesByParent = [];

    foreach ($parents as $parentId => $name) {
        $sizesByParent[$parentId] = DB::table('products')
            ->join('sizes', 'sizes.id', '=', 'products.size_id')
            ->where('products.parent_id', $parentId)
            ->select(
                'sizes.id',
                'sizes.name',
                'sizes.sort'
            )
            ->distinct()
            ->orderBy('sizes.sort')
            ->get();
    }


        return view('backend.batch.report-batch')->with([
            'batch' => $batch,
            'batchOne' => $batchOne,
            'order' => $order,

            'sizes' => $sizes,
            'colors' => $colors,

            'batchItems' => $batchItems,
            'matrix' => $matrix,
            'parents' => $parents,
            'totals' => $totals,
            'sizesByParent' => $sizesByParent,
        ]);

    }

    public function index_conformed()
    {
        return view('backend.batch.index-conformed');
    }

    public function index_manufacturing()
    {
        return view('backend.batch.index-manufacturing');
    }

    public function index_personalization()
    {
        return view('backend.batch.index-personalization');
    }

    public function index_shipment()
    {
        return view('backend.batch.index-shipment');
    }


    public function destroy(Batch $batch)
    {
        // foreach($batch->batch_product as $batch_product){
        //     $batch_product->parent()->increment('active', abs($batch_product->quantity));
        //     $batch_product->children()->update(['active' => 0]);
        // }

        // if($batch->id){
        //     $batch->update(['active' => 0]);
        //     $batch->delete();
        // }

        return redirect()->back()->withFlashSuccess(__('The batch was successfully deleted.'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use DB;

class ProductionBatchController extends Controller
{
    public function output($productionBatch, bool $grouped = false)
    {
        $productionBatch = ProductionBatch::find($productionBatch);

        if($productionBatch->status_id != 15){
            abort(401);
        }

        // Obtener todos los lotes relacionados
        $relatedBatches = $productionBatch->getMoreOutput();
        $batchIds = [$productionBatch->id];
        
        if($relatedBatches && $relatedBatches->count() > 1){
            // Si hay lotes relacionados, los incluimos en la consulta
            $batchIds = $relatedBatches->pluck('id')->toArray();
            array_push($batchIds, $productionBatch->id);
        }

        $orderServices = DB::table('production_batch_items as a')
                ->selectRaw('
                    b.name as product_name,
                    b.code as product_code,
                    b.color_id as color_name,
                    b.size_id as size_name,
                    b.brand_id as brand_name,
                    sum(a.output_quantity * z.price) as sum_total,
                    sum(a.output_quantity) as sum,
                    min(z.price) as min_price,
                    max(z.price) as max_price,
                    min(z.price) <> max(z.price) as omg,
                    a.output_quantity as total_by_product
                ')
                ->join('products as b', 'a.product_id', '=', 'b.id')
                ->join('product_order as z', 'a.product_id', '=', 'z.id')
                ->whereIn('batch_id', $batchIds)
                ->where('b.type', '=', 0)
                ->groupBy('b.id')
                ;

        $orderGroup = DB::table('production_batch_items as a')
            ->selectRaw('
                c.name as product_name,
                c.code as product_code,
                d.name as color_name,
                e.name as size_name,
                f.name as brand_name,
                min(z.price) as min_price,
                max(z.price) as max_price,
                min(z.price) <> max(z.price) as omg,
                sum(a.output_quantity * z.price) as sum_total,
                sum(a.output_quantity) as sum,
                count(*) as total_by_product

            ')
            ->join('products as b', 'a.product_id', '=', 'b.id')
            ->join('products as c', 'b.parent_id', '=', 'c.id')
            ->join('colors as d', 'b.color_id', '=', 'd.id')
            ->join('sizes as e', 'b.size_id', '=', 'e.id')
            ->join('brands as f', 'c.brand_id', '=', 'f.id')

            ->join('production_batches as pb', 'a.batch_id', '=', 'pb.id')
            ->join('product_order as z', function($join) {
                $join->on('pb.order_id', '=', 'z.order_id')
                     ->on('a.product_id', '=', 'z.product_id');
            })

            ->groupBy('b.parent_id', 'b.color_id', 'z.price')
            ->whereIn('batch_id', $batchIds)
            ->orderBy('product_name')
            ->orderBy('color_name')
            ->union($orderServices)
            ->get();


        return view('backend.order.output', [
            'productionBatch' => $productionBatch,
            'grouped' => $grouped,
            'orderGroup' => $orderGroup,
        ]);
    }
}

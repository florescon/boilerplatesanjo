<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use DB;

class ProcessAndInventory extends Component
{
    public function render()
    {
        $parentIds = DB::table('products')
            ->whereNull('deleted_at')
            ->where('type', true)
            ->groupBy('parent_id')
            ->havingRaw('SUM(stock) != 0')
            ->orderByRaw('SUM(stock) DESC')
            ->pluck('parent_id');

        $productsP = DB::table('products')
            ->join('products as parents', 'products.parent_id', '=', 'parents.id')
            ->whereNull('products.deleted_at')
            ->where('products.type', true)
            ->whereNull('parents.parent_id')
            ->whereIn('parents.id', $parentIds)
            ->select(
                'products.parent_id',
                'products.size_id',
                'products.color_id',
                'products.stock',
                'parents.cost as parent_cost',
                'parents.name as parent_name',
                'parents.code as parent_code'
            )
            ->get();

        $productsActive = DB::table('production_batch_items as a')
            ->join('products as pp', 'a.product_id', '=', 'pp.id')
            ->join('products as parents', 'pp.parent_id', '=', 'parents.id')
            ->where('a.active', '>', 0)
            ->whereNull('pp.deleted_at')
            ->where('pp.type', true)
            ->whereNull('parents.parent_id')
            ->select(
                'a.active',
                'a.status_id',
                'pp.parent_id',
                'pp.size_id',
                'pp.color_id',
                'pp.stock',
                'parents.cost as parent_cost',
                'parents.name as parent_name',
                'parents.code as parent_code'
            )
            ->get();



        $activeMatrix = [];
        $activeTotals = [];
        $activeTotalsByColor = [];
        $activeTotalsBySize = [];
        $activeCosts = [];

        foreach ($productsActive as $item) {

            $parentId = $item->parent_id ?: 0;
            $colorId = $item->color_id;
            $sizeId = $item->size_id;

            $active = (int) ($item->active ?? 0);
            $cost = (float) ($item->parent_cost ?? 0);

            // Costo del producto padre
            $activeCosts[$parentId] = $cost;

            // Active por parent / color / talla
            $activeMatrix[$parentId][$colorId][$sizeId] =
                ($activeMatrix[$parentId][$colorId][$sizeId] ?? 0) + $active;

            // Active total por color
            $activeTotalsByColor[$parentId][$colorId] =
                ($activeTotalsByColor[$parentId][$colorId] ?? 0) + $active;

            // Active total por talla
            $activeTotalsBySize[$parentId][$sizeId] =
                ($activeTotalsBySize[$parentId][$sizeId] ?? 0) + $active;

            // Active total producto
            $activeTotals[$parentId] =
                ($activeTotals[$parentId] ?? 0) + $active;
        }


        // ============================================
        // TOTALES DE PRODUCCIÓN
        // ============================================

        $totalProduction = collect($activeTotals)->sum();

        $totalProductionValue = 0;

        foreach ($activeTotals as $parentId => $active) {

            $cost = $activeCosts[$parentId] ?? 0;

            $totalProductionValue += $active * $cost;
        }

        $matrix = [];
        $parents = [];
        $totals = [];
        $sizesByParent = [];
        $colorsByParent = [];

        $totalCost = 0;

        foreach ($productsP as $item) {

            $parentId = $item->parent_id ?: 0;
            $qty = (int) ($item->stock ?? 0);

            $cost = (float) ($item->parent_cost ?? 0);

            $parents[$parentId] = $item->parent_name
                ? $item->parent_code . '  ' . $item->parent_name
                : 'Sin producto padre';

            $matrix[$parentId][$item->color_id][$item->size_id] = $item;

            // Total por color
            $totals[$parentId]['colors'][$item->color_id] =
                ($totals[$parentId]['colors'][$item->color_id] ?? 0) + $qty;

            // Total por talla
            $totals[$parentId]['sizes'][$item->size_id] =
                ($totals[$parentId]['sizes'][$item->size_id] ?? 0) + $qty;

            // Total producto
            $totals[$parentId]['product'] =
                ($totals[$parentId]['product'] ?? 0) + $qty;

        // Costo del producto padre
        $totals[$parentId]['cost'] = $cost;

        }

        $totalQty = collect($totals)->sum('product');

        $totalValue = collect($totals)->sum(function ($total) {
            return ($total['product'] ?? 0) * ($total['cost'] ?? 0);
        });

        // ============================================
        // RESTAURAR EL ORDEN DE $parentIds
        // ============================================

        $parents = collect($parentIds)
            ->mapWithKeys(function ($parentId) use ($parents) {

                if (isset($parents[$parentId])) {
                    return [
                        $parentId => $parents[$parentId]
                    ];
                }

                return [];

            })
            ->all();


        // Tallas y colores
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

            $colorsByParent[$parentId] = DB::table('products')
                ->join('colors', 'colors.id', '=', 'products.color_id')
                ->where('products.parent_id', $parentId)
                ->select(
                    'colors.id',
                    'colors.name'
                )
                ->distinct()
                ->orderBy('colors.name')
                ->get();
        }

        return view('backend.charts.process-and-inventory',[
            'matrix' => $matrix,
            'parents' => $parents,
            'totals' => $totals,
            'sizesByParent' => $sizesByParent,
            'colorsByParent' => $colorsByParent,
            'totalQty' => $totalQty,
            'totalValue' => $totalValue,


            'activeMatrix' => $activeMatrix,
            'activeTotals' => $activeTotals,
            'activeTotalsByColor' => $activeTotalsByColor,
            'activeTotalsBySize' => $activeTotalsBySize,

            'totalProduction' => $totalProduction,
            'totalProductionValue' => $totalProductionValue,
        ]);
    }
}

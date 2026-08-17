<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use DB;

class Historic extends Component
{
public $graphSankey = [];

public function mount()
{
    $this->graphSankey = $this->getSankeyData();
}

public function loadSankey()
{
    $this->emit('graphMaterial', $this->graphSankey);
}

protected function actualizarGraficas()
{
    $this->graphSankey = $this->getSankeyData();

    $this->emit('graphMaterial', $this->graphSankey);
}
    public function getSankeyData()
    {
        $fecha = now()->subDays(15);

        $data = DB::table('material_orders as mo')
            ->join('product_order as po', 'po.id', '=', 'mo.product_order_id')
            ->join('products as p', 'p.id', '=', 'po.product_id')
            ->join('products as parent', 'parent.id', '=', 'p.parent_id')
            ->join('colors as c', 'c.id', '=', 'p.color_id')
            ->join('materials as m', 'm.id', '=', 'mo.material_id')
            ->join('families as f', 'f.id', '=', 'm.family_id')
            ->select([
                'mo.order_id',

                'p.parent_id',
                'parent.code as parent_code',
                'parent.name as parent_name',

                'p.color_id',
                'c.name as color_name',

                'f.id as family_id',
                'f.name as family_name',

                'm.id as material_id',
                'm.name as material_name',

                DB::raw('ROUND(SUM(po.quantity), 1) as quantity_order'),
                DB::raw('ROUND(SUM(mo.quantity), 1) as quantity_material'),
            ])
            ->where('mo.order_id', 3410)
            ->where('mo.deleted_at', null)
            ->where('po.deleted_at', null)
            // ->whereDate('mo.created_at', '>=', $fecha)
            ->groupBy([
                'mo.order_id',

                'p.parent_id',
                'parent.code',
                'parent.name',

                'p.color_id',
                'c.name',

                'f.id',
                'f.name',

                'm.id',
                'm.name',
            ])
            ->get();


        $nodes = [];
        $edges = [];


        /*
        |--------------------------------------------------------------------------
        | Función para crear/agrupar edges
        |--------------------------------------------------------------------------
        */

        $addEdge = function ($source, $target, $value) use (&$edges) {

            $key = $source . '|' . $target;

            if (!isset($edges[$key])) {

                $edges[$key] = [
                    'source' => $source,
                    'target' => $target,
                    'value' => 0,
                ];
            }

            $edges[$key]['value'] += (float) $value;
        };


        foreach ($data as $row) {

            /*
            |--------------------------------------------------------------------------
            | IDs
            |--------------------------------------------------------------------------
            */

            $orderId = 'order_' . $row->order_id;

            $parentId = 'parent_' . $row->parent_id;

            $colorId = 'color_' . $row->parent_id . '_' . $row->color_id;

            $familyId = 'family_' . $row->family_id;

            $materialId = 'material_' . $row->material_id;


            /*
            |--------------------------------------------------------------------------
            | NODO ORDEN
            |--------------------------------------------------------------------------
            */

            $nodes[$orderId] = [
                'id' => $orderId,
                'title' => 'Orden #' . $row->order_id,
                'color' => '#FF7F50',
            ];


            /*
            |--------------------------------------------------------------------------
            | NODO PRODUCTO PADRE
            |--------------------------------------------------------------------------
            */

            $nodes[$parentId] = [
                'id' => $parentId,
                'title' => $row->parent_code,
                'color' => '#6366F1',
            ];


            /*
            |--------------------------------------------------------------------------
            | NODO COLOR
            |--------------------------------------------------------------------------
            */

            $nodes[$colorId] = [
                'id' => $colorId,
                'title' => $row->color_name,
                'color' => '#EC4899',
            ];


            /*
            |--------------------------------------------------------------------------
            | NODO FAMILIA
            |--------------------------------------------------------------------------
            */

            $nodes[$familyId] = [
                'id' => $familyId,
                'title' => $row->family_name,
                'color' => '#F97316',
            ];


            /*
            |--------------------------------------------------------------------------
            | NODO MATERIAL
            |--------------------------------------------------------------------------
            */

            $nodes[$materialId] = [
                'id' => $materialId,
                'title' => $row->material_name,
                'color' => '#10B981',
            ];


            /*
            |--------------------------------------------------------------------------
            | ORDEN → PRODUCTO PADRE
            |--------------------------------------------------------------------------
            */

            $addEdge(
                $orderId,
                $parentId,
                $row->quantity_order
            );


            /*
            |--------------------------------------------------------------------------
            | PRODUCTO PADRE → COLOR
            |--------------------------------------------------------------------------
            */

            $addEdge(
                $parentId,
                $colorId,
                $row->quantity_order
            );


            /*
            |--------------------------------------------------------------------------
            | COLOR → FAMILIA
            |--------------------------------------------------------------------------
            */

            $addEdge(
                $colorId,
                $familyId,
                $row->quantity_material
            );


            /*
            |--------------------------------------------------------------------------
            | FAMILIA → MATERIAL
            |--------------------------------------------------------------------------
            */

            $addEdge(
                $familyId,
                $materialId,
                $row->quantity_material
            );
        }

        return [
            'nodes' => array_values($nodes),
            'edges' => array_values($edges),
        ];
    }



    public function render()
    {
        return view('backend.charts.graph-historic');
    }
}

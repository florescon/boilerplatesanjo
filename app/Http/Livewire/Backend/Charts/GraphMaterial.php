<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Material;

class GraphMaterial extends Component
{
    public function chartData()
    {
        $materials = Material::query()
        ->join('families', 'families.id', '=', 'materials.family_id')
        ->join('units', 'units.id', '=', 'materials.unit_id')
        ->select(
            'families.name as family',
            'units.name as unit',
            DB::raw('SUM(materials.stock) as stock')
        )
        ->groupBy('families.name', 'units.name')
        ->get();


        $nodes = [];
        $edges = [];

        foreach ($materials as $row) {

            $nodes[$row->family] = [
                'id' => $row->family,
                'title' => $row->family
            ];

            $nodes[$row->unit] = [
                'id' => $row->unit,
                'title' => $row->unit
            ];


            if ((float)$row->stock > 0) {
                $edges[] = [
                    'source' => $row->family,
                    'target' => $row->unit,
                    'value' => (float)$row->stock
                ];
            }
        }

        $data = [
            'nodes' => array_values($nodes),
            'edges' => $edges
        ];

        // dd($data);

        return $data;
    }


    public function render()
    {
        $data = $this->chartData();

        return view('backend.charts.graph-material',[
            'chartData'=>$this->chartData()
        ]);
    }
}

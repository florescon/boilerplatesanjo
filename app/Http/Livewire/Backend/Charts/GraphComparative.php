<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraphComparative extends Component
{

    public $chartData = [];

    public function mount()
    {
        $this->loadChart();
    }

    public function loadChart()
    {
        $currentYear = Carbon::now()->year;
        $previousYear = $currentYear - 1;


        $orders = DB::table('product_order as p')
            ->join('orders as or','or.id','=','p.order_id')
            ->select(
                DB::raw('YEAR(p.created_at) as year'),
                DB::raw('DATE(p.created_at) as date'),
                DB::raw('SUM(p.quantity) as total')
            )
            ->where('or.deleted_at', null)
            ->where('or.type', true)
            ->where('or.from_store', null)
            ->where('p.deleted_at', null)
            ->where('p.type', 1)
            ->whereYear('p.created_at', '>=', $previousYear)
            ->groupBy(
                DB::raw('YEAR(p.created_at)'),
                DB::raw('DATE(p.created_at)')
            )
            ->orderBy('date')
            ->get();


        $current = [];
        $previous = [];


        foreach ($orders as $row) {

            $point = [
                'x' => Carbon::parse($row->date)->timestamp * 1000,
                'y' => (int) $row->total
            ];


            if ($row->year == $currentYear) {
                $current[] = $point;
            }


            if ($row->year == $previousYear) {
                // cambiar el año para comparar misma posición del calendario
                $point['x'] = Carbon::parse($row->date)
                    ->year($currentYear)
                    ->timestamp * 1000;

                $previous[] = $point;
            }
        }


        $this->chartData = [
            [
                'name' => "Año $currentYear",
                'data' => $current
            ],
            [
                'name' => "Año $previousYear",
                'data' => $previous
            ]
        ];
    }

    public function render()
    {
        return view('backend.charts.graph-comparative');
    }
}

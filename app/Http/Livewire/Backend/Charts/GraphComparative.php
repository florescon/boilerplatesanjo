<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraphComparative extends Component
{
    public $chartData = [];
    public $loadPriceChart = [];

    public function mount()
    {
        $this->loadChart();
    }

    public function loadChart()
    {
        $currentYear = Carbon::now()->year;
        $previousYear = $currentYear - 1;

        $orders = DB::table('product_order as p')
            ->join('orders as or', 'or.id', '=', 'p.order_id')
            ->select(
                DB::raw('YEAR(p.created_at) as year'),
                DB::raw('DATE(p.created_at) as date'),

                // Cantidad de productos
                DB::raw('ROUND(SUM(p.quantity), 0) as total'),

                // Importe total
                DB::raw('ROUND(SUM(p.quantity * p.price), 0) as amount')
            )
            ->whereNull('or.deleted_at')
            ->where('or.type', true)
            ->whereNull('or.from_store')
            ->whereNull('p.deleted_at')
            ->where('p.type', 1)
            ->whereIn(
                DB::raw('YEAR(p.created_at)'),
                [$previousYear, $currentYear]
            )
            ->groupBy(
                DB::raw('YEAR(p.created_at)'),
                DB::raw('DATE(p.created_at)')
            )
            ->orderBy('date')
            ->get();

        $current = [];
        $previous = [];

        $currentAmount = [];
        $previousAmount = [];

        foreach ($orders as $row) {

            // ==========================
            // CANTIDAD
            // ==========================

            $point = [
                'x' => Carbon::parse($row->date)->timestamp * 1000,
                'y' => (int) $row->total
            ];

            // ==========================
            // IMPORTE
            // ==========================

            $amountPoint = [
                'x' => Carbon::parse($row->date)->timestamp * 1000,
                'y' => (float) $row->amount
            ];

            if ($row->year == $currentYear) {

                $current[] = $point;
                $currentAmount[] = $amountPoint;
            }

            if ($row->year == $previousYear) {

                // Normalizamos el año anterior al año actual
                $normalizedDate = Carbon::parse($row->date)
                    ->year($currentYear);

                $point['x'] = $normalizedDate->timestamp * 1000;
                $previous[] = $point;

                $amountPoint['x'] = $normalizedDate->timestamp * 1000;
                $previousAmount[] = $amountPoint;
            }
        }

        // Primera gráfica: cantidades
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

        // Segunda gráfica: importes
        $this->loadPriceChart = [
            [
                'name' => "Año $currentYear",
                'data' => $currentAmount
            ],
            [
                'name' => "Año $previousYear",
                'data' => $previousAmount
            ]
        ];
    }


    public function render()
    {
        return view('backend.charts.graph-comparative');
    }
}

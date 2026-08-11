<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraphProjection extends Component
{
    public $periodo = '12sem';

    public $series = [];

    public function mount()
    {
        $this->loadChart();
    }

    protected $queryString = [
        'periodo' => ['except' => '12sem'],
    ];


    protected function fechaInicio()
    {
        switch ($this->periodo) {

            case '1sem':
                return now()->addWeek(1)
                    ->subYear();

            case '4sem':
                return now()->addWeeks(4)
                    ->subYear();

            case '8sem':
                return now()->addWeeks(8)
                    ->subYear();

            case '12sem':
                return now()->addWeeks(12)
                    ->subYear();

            default:
                return now()->addWeeks(12)
                    ->subYear();
        }


    return isset($periodos[$this->periodo])
        ? $periodos[$this->periodo]
        : now()->subYear();

    }

    private function topProductsQuery($start, $fecha)
    {
        return DB::table('product_order as ppo')
            ->join('orders as orr','orr.id','=','ppo.order_id')
            ->join('products as pp', 'pp.id', '=', 'ppo.product_id')
            ->join('products as pparent', 'pparent.id', '=', 'pp.parent_id')
            ->select(
                'pp.parent_id',
                'pparent.name as product_name',
                'pparent.code as product_code',
                DB::raw('SUM(ppo.quantity) as product_total')
            )
            ->whereNull('orr.deleted_at')
            ->where('orr.type', true)
            ->whereNull('orr.from_store')
            ->where('ppo.type', 1)
            ->whereNull('ppo.deleted_at')
            ->whereBetween('ppo.created_at', [$start, $fecha])
            ->groupBy('pp.parent_id')
            ->orderByDesc('product_total')
            ->limit(20);
    }

    public function topProducts()
    {
        $fecha = $this->fechaInicio();
        $start = now()->subYear();

        // dd($this->topProductsQuery($start, $fecha)->get());
        return $this->topProductsQuery($start, $fecha)->get();
    }

    public function updatedPeriodo($value)
    {

        $permitidos = [
            '1sem',
            '4sem',
            '8sem',
            '12sem',
        ];

        if (!in_array($value, $permitidos)) {
            $this->periodo = '12sem';
        }

        $this->actualizarGraficas();
    }

    protected function actualizarGraficas()
    {
        $this->emit('graphWeek', $this->loadChart());
    }

    private function loadChart()
    {
        $fecha = $this->fechaInicio();

        $now = now();
        // $now = Carbon::parse('2026-3-06');

        $start = $now->copy()->subYear();

        $lastYear = $now->copy()->subYear()->year;

        /*
        |--------------------------------------------------------------------------
        | Top 20 productos padre
        |--------------------------------------------------------------------------
        */

    $topProductsQuery = $this->topProductsQuery($start, $fecha);

        /*
        |--------------------------------------------------------------------------
        | Heatmap
        |--------------------------------------------------------------------------
        */
   $rows = DB::table('product_order as po')
        ->join('orders as or','or.id','=','po.order_id')
        ->join('products as p', 'p.id', '=', 'po.product_id')
        ->join('products as parent', 'parent.id', '=', 'p.parent_id')
        ->joinSub(
            $topProductsQuery,
            'top',
            function ($join) {
                $join->on('top.parent_id', '=', 'p.parent_id');
            }
        )
        ->select(
            'parent.name',
            'parent.code',
            'top.product_total',
            DB::raw('YEARWEEK(po.created_at, 3) as year_week'),
            DB::raw('SUM(po.quantity) as total')
        )
        ->whereNull('or.deleted_at')
        ->where('or.type', true)
        ->whereNull('or.from_store')
        ->where('po.type', 1)
        ->whereNull('po.deleted_at')
        ->whereBetween('po.created_at', [$start, $fecha])
        ->groupBy(
            // 'parent.name',
            'parent.code',
            'top.product_total',
            DB::raw('YEARWEEK(po.created_at, 3)')
        )
        ->orderBy('top.product_total')
        ->orderBy('year_week')
        ->get();

        // dd($topProductsQuery->get());

        /*
        |--------------------------------------------------------------------------
        | Semanas
        |--------------------------------------------------------------------------
        */
        $weeks = [];

        $date = $start->copy();

$date = $start->copy()->startOfWeek();

while ($date <= $fecha) {
    $weeks[] = $date->format('oW');
    $date->addWeek();
}        /*
        |--------------------------------------------------------------------------
        | Apex Series
        |--------------------------------------------------------------------------
        */

        $series = [];

        foreach ($rows->groupBy('code') as $product => $items) {

            $itemsByWeek = $items->keyBy(function ($item) {
                return (string) $item->year_week;
            });

            $data = [];

            foreach ($weeks as $yearWeek) {

                $row = $itemsByWeek->get($yearWeek);

                $data[] = [
                    'x' => $yearWeek,
                    'y' => $row ? (int) $row->total : 0,
                ];
            }

            $series[] = [
                'name' => $items->first()->code,
                'data' => $data,
            ];
        }
        $this->series = $series;

        return $this->series;
    }

    public function render()
    {
        return view('backend.charts.graph-projection');
    }
}

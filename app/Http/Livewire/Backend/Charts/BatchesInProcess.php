<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Exports\Charts\ProceedInOrderExport;
use Symfony\Component\HttpFoundation\Response;
use Excel;

class BatchesInProcess extends Component
{
    public $months = 12;

    public $chartData = [];

    public function setMonths($months)
    {
        $this->months = $months;
    }

    public function exportMaatwebsite($extension)
    {   
        return Excel::download(
            new ProceedInOrderExport,
            'reporte_ventas.xlsx'
        );

        // abort_if(!in_array($extension, ['csv', 'xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        // return Excel::download(new ProceedInOrderExport('reporte_ventas.'.$extension));
    }

public function getBatchChartData()
{
    return DB::table('batches as b')
        ->join('batch_items as bi', 'bi.batch_id', '=', 'b.id')
        ->join('product_order as po', 'po.id', '=', 'bi.product_order_id')
        ->leftJoin('batch_operations as bo', 'bo.batch_item_id', '=', 'bi.id')
        ->where('b.status_name', 'pending')
        ->select(
            'b.id as batch_id',
            'b.status_name',

            DB::raw('SUM(po.quantity) as total_quantity'),

            DB::raw("
                SUM(
                    CASE 
                        WHEN bo.status_name='completed'
                        THEN bi.quantity
                        ELSE 0
                    END
                ) as completed_quantity
            "),

            DB::raw("
                SUM(
                    CASE 
                        WHEN bo.status_name='pending'
                        THEN bi.quantity
                        ELSE 0
                    END
                ) as pending_quantity
            ")
        )

        ->groupBy(
            'b.id',
            'b.status_name'
        )

        ->get();
}



public function mount()
{
    $batches = $this->getBatchChartData();

    $this->chartData = [
        'categories' => $batches->pluck('batch_id'),
        'series' => [
            [
                'name' => 'Completado',
                'data' => $batches
                    ->pluck('completed_quantity')
            ],
            [
                'name' => 'Pendiente',
                'data' => $batches
                    ->pluck('pending_quantity')
            ],
        ]
    ];
}

    public function render()
    {
        return view('backend.charts.batches-in-process');
    }
}

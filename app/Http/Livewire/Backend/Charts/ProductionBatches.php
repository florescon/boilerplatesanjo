<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Exports\Charts\ProceedInOrderExport;
use Symfony\Component\HttpFoundation\Response;
use Excel;

class ProductionBatches extends Component
{
    public $months = 12;

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

    public function render()
    {

        $ordersQuantity = DB::table('orders as o')
            ->join('product_order as po', 'po.order_id', '=', 'o.id')
            ->join('products as p', 'p.id', '=', 'po.product_id')
            ->where('o.type', 1)
            ->whereNull('o.from_store')
            ->where('o.created_at', '>=', now()->subMonths($this->months))
            ->select(
                DB::raw("DATE_FORMAT(o.created_at, '%Y-%m') as month"),
                DB::raw("SUM(CASE WHEN p.type = 1 THEN po.quantity ELSE 0 END) as products"),
                DB::raw("SUM(CASE WHEN p.type = 0 THEN po.quantity ELSE 0 END) as services")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();


        $months = $ordersQuantity->pluck('month');
        $products = $ordersQuantity->pluck('products');
        $services = $ordersQuantity->pluck('services');

            return view('backend.charts.proceed-in-order', [
                'months' => $months,
                'products' => $products,
                'services' => $services,
            ]);
    }
}

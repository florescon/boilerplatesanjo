<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Exports\Charts\ProceedInOrderExport;
use Symfony\Component\HttpFoundation\Response;
use Excel;

class ProceedInOrder extends Component
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
    }

    public function render()
    {
        $startDate = now()
            ->subMonths($this->months - 1)
            ->startOfMonth();

        $endDate = now()
            ->addMonth()
            ->startOfMonth();

        $ordersQuantity = DB::table('orders as o')
            ->join('product_order as po', 'po.order_id', '=', 'o.id')
            ->join('products as p', 'p.id', '=', 'po.product_id')
            ->where('o.type', 1)
            ->where('o.deleted_at', null)
            ->where('po.deleted_at', null)
            ->whereNull('o.from_store')
            ->whereBetween('o.created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(o.created_at, '%Y-%m') as month"),
                DB::raw("SUM(CASE WHEN p.type = 1 THEN po.quantity ELSE 0 END) as products"),
                DB::raw("SUM(CASE WHEN p.type = 0 THEN po.quantity ELSE 0 END) as services")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            $products = $ordersQuantity->pluck('products')->values()->toArray();
            $services = $ordersQuantity->pluck('services')->values()->toArray();


            $monthss = $ordersQuantity
                ->pluck('month')
                ->values()
                ->toArray();

            // dd($monthss);

            return view('backend.charts.proceed-in-order', [
                'monthss' => $monthss,
                'products' => $products,
                'services' => $services,
            ]);
    }
}

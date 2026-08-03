<?php

namespace App\Exports\Charts;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class ProceedInOrderExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('orders as o')
            ->join('product_order as po', 'po.order_id', '=', 'o.id')
            ->join('products as p', 'p.id', '=', 'po.product_id')
            ->where('o.type', 1)
            ->whereNull('o.from_store')
            ->where('o.created_at', '>=', now()->subMonths(12))
            ->select(
                'o.id',
                'o.created_at',
                DB::raw("SUM(CASE WHEN p.type = 1 THEN po.quantity ELSE 0 END) as products"),
                DB::raw("SUM(CASE WHEN p.type = 0 THEN po.quantity ELSE 0 END) as services")
            )
            ->groupBy(
                'o.id',
                'o.created_at'
            )
            ->get();    
    }
}

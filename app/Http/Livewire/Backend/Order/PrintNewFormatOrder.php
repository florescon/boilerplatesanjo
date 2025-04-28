<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use DB;

class PrintNewFormatOrder extends Component
{
    public Order $order;    

    public bool $width = false;
    public bool $prices = false;
    public bool $breakdown = false;
    public bool $general = true;
    public bool $details = true;

    protected $queryString = [
        'width' => ['except' => FALSE],
        'prices' => ['except' => FALSE],
        'breakdown' => ['except' => FALSE],
        'general' => ['except' => FALSE],
        'details' => ['except' => FALSE],
    ];

    public function render()
    {
        $orderServices = DB::table('product_order as a')
                ->selectRaw('
                    b.name as product_name,
                    b.code as product_code,
                    b.color_id as color_name,
                    b.size_id as size_name,
                    b.brand_id as brand_name,
                    min(a.price) as min_price,
                    max(a.price) as max_price,
                    min(a.price) <> max(a.price) as omg,
                    sum(a.quantity) as sum,
                    sum(a.quantity * a.price) as sum_total,
                    a.quantity as total_by_product
                ')
                ->join('products as b', 'a.product_id', '=', 'b.id')
                ->where('order_id', $this->order->id)
                ->where('b.type', '=', 0)
                ->groupBy('b.id', 'a.price')
                ;

        $orderGroup = DB::table('product_order as a')
            ->selectRaw('
                c.name as product_name,
                c.code as product_code,
                d.name as color_name,
                e.name as size_name,
                min(a.price) as min_price,
                max(a.price) as max_price,
                f.name as brand_name,
                min(a.price) <> max(a.price) as omg,
                sum(a.quantity) as sum,
                sum(a.quantity * a.price) as sum_total,
                count(*) as total_by_product
            ')
            ->join('products as b', 'a.product_id', '=', 'b.id')
            ->join('products as c', 'b.parent_id', '=', 'c.id')
            ->join('colors as d', 'b.color_id', '=', 'd.id')
            ->join('sizes as e', 'b.size_id', '=', 'e.id')
            ->join('brands as f', 'c.brand_id', '=', 'f.id')  // Agregamos el join con la tabla brands
            ->groupBy('b.parent_id', 'b.color_id', 'a.price')
            ->where('order_id', $this->order->id)
            ->orderBy('product_name')
            ->orderBy('color_name')
            ->union($orderServices)
            ->get();


        // dd($this->order->products->toArray());

        $tablesData = $this->order->getSizeTablesData();

        return view('backend.order.livewire.print-new-format-order', [
            'tablesData' => $tablesData,
            'orderGroup' => $orderGroup,
        ]);
    }
}

<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class GraphFlagshipProduct extends Component
{
    public $periodo = '30days';
    
    public $limitProducts = '6';

    public $treemap = [];
    public $average = [];

    protected $queryString = [
        'periodo' => ['except' => '30days'],
    ];

    public function mount()
    {
        $this->treemap = $this->treemapProductos();
        $this->average = $this->averagePriceProductos();
    }

    protected function fechaInicio()
    {
        switch ($this->periodo) {

            case '7days':
                return now()->subDays(7);

            case '15days':
                return now()->subDays(15);

            case '30days':
                return now()->subDays(30);

            case '3months':
                return now()->subMonths(3);

            case '6months':
                return now()->subMonths(6);

            case '1year':
                return now()->subYear();

            case '2years':
                return now()->subYears(2);

            case '4years':
                return now()->subYears(4);

            default:
                return now()->subYear();
        }


    return isset($periodos[$this->periodo])
        ? $periodos[$this->periodo]
        : now()->subYear();

    }

    public function updatedPeriodo($value)
    {
        $this->actualizarGraficas();

        $permitidos = [
            '7days',
            '15days',
            '30days',
            '3months',
            '6months',
            '1year',
            '2years',
            '4years',
        ];

        if (!in_array($value, $permitidos)) {
            $this->periodo = '1year';
        }

        $this->actualizarGraficas();
    }

    public function updatedLimitProducts($value)
    {

        $this->actualizarGraficas();
    }

    protected function actualizarGraficas()
    {
        $this->emit('treemapProductos', $this->treemapProductos());
        $this->emit('averagePriceProductos', $this->averagePriceProductos());
    }

    protected function treemapProductos()
    {
        $fecha = $this->fechaInicio();

        $data = DB::table('product_order')
            ->join('orders','orders.id','=','product_order.order_id')
            ->join('products as child','child.id','=','product_order.product_id')
            ->join('products as parent','parent.id','=','child.parent_id')
            ->whereDate('product_order.created_at','>=',$fecha)
            ->where('orders.type',true)
            ->where('child.type', true)
            ->where('orders.from_store', null)
            ->where('child.deleted_at', null)
            ->where('orders.deleted_at', null)
            ->where('product_order.deleted_at', null)
            ->where('parent.deleted_at', null)
            ->select(
                'parent.name',
                DB::raw('SUM(product_order.quantity) total')
            )
            ->groupBy('parent.name')
            ->orderByDesc('total')
            ->limit($this->limitProducts)
            ->get();

        return $data->map(fn($item)=>[
            'x'=>$item->name,
            'y'=>(int)$item->total
        ]);
    }

    protected function averagePriceProductos()
    {
        $fecha = $this->fechaInicio();
        
        $data = DB::table('product_order')
            ->join('orders', 'orders.id', '=', 'product_order.order_id')
            ->join('products as child', 'child.id', '=', 'product_order.product_id')
            ->join('products as parent', 'parent.id', '=', 'child.parent_id')
            ->whereDate('orders.created_at', '>=', $fecha)
            ->where('orders.type',true)
            ->where('child.type', true)
            ->whereNull('child.deleted_at')
            ->whereNull('parent.deleted_at')
            ->where('orders.from_store', null)
            ->whereNull('orders.deleted_at')
            ->whereNull('product_order.deleted_at')
            ->select(
                'parent.name',
                'parent.code',

                DB::raw('ROUND(AVG(parent.cost),2) as precio_compra'),

                DB::raw('ROUND(AVG(product_order.price),2) as precio_venta'),

                DB::raw('SUM(product_order.quantity) as productos'),

                DB::raw('
                    ROUND(
                        SUM(product_order.price - parent.cost),
                    2) as utilidad
                '),

                DB::raw('
                    ROUND(
                        (
                            (
                                AVG(product_order.price) - AVG(parent.cost)
                            ) / AVG(parent.cost)
                        ) * 100,
                    2) as porcentaje_ganancia
                ')
            )
            ->groupBy('parent.name')
            ->orderByDesc('productos')
            ->limit($this->limitProducts)
            ->get();


        return [
            'categories' => $data->pluck('code'),

            'precioCompra' => $data->pluck('precio_compra')
                ->map(fn($v)=>(float)$v),

            'precioVenta' => $data->pluck('precio_venta')
                ->map(fn($v)=>(float)$v),

            'utilidad' => $data->map(function($item) {
                return $item->porcentaje_ganancia ;
            }),

            'productos' => $data->pluck('productos')
                ->map(fn($v)=>(int)$v),
        ];
    }

    public function render()
    {
        return view('backend.charts.graph-flagship-product');
    }
}

<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GraphByProduct extends Component
{
    public $periodo = '30days';

    public $query;

    public $heatmap = [];

    public $productsChart = [
        'series' => [],
        'categories' => [],
    ];

    public $materialsChart = [
        'series' => [],
        'categories' => [],
    ];


    public $ventasTotales = 0;
    public $productosVendidos = 0;
    public $utilidadTotal = 0;
    public $margenUtilidad = 0;

    public $inventarioTotal = 0; 
    public $valorInventario = 0;
    public $subproductoMasVendido;
    public $subproductoMayorInventario;


    public $selectedProduct = null;

    protected $queryString = [
        'periodo' => ['except' => '30days'],
    ];

    public function mount()
    {
        // $this->heatmap = $this->heatmapProductos();
    }

    public function updatedQuery()
    {
        $this->products = Product::with('parent', 'brand', 'color', 'size')
            ->whereRaw("code LIKE \"%$this->query%\"")
            ->orWhereRaw("name LIKE \"%$this->query%\"")
            ->onlyProductsParent()
            ->get()->take(8)
            ->toArray();

       // $this->selectedProduct = null;
    }

    public function reset_search()
    {
        $this->query = '';
        $this->products = [];
        $this->selectedProduct = null;
    }

    public function selectProduct($idProduct)
    {
        $product = Product::with('children', 'color', 'size')->findOrFail($idProduct);

        if ($product) {

            $this->selectedProduct = Product::with('children', 'color', 'size')->findOrFail($idProduct);
            $this->query = '';
        }

        $this->actualizarGraficas();

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

            default:
                return now()->subYear();
        }


    return isset($periodos[$this->periodo])
        ? $periodos[$this->periodo]
        : now()->subYear();

    }

    public function updatedPeriodo($value)
    {
        $permitidos = [
            '7days',
            '15days',
            '30days',
            '3months',
            '6months',
            '1year',
            '2years',
        ];

        if (!in_array($value, $permitidos)) {
            $this->periodo = '1year';
        }

        $this->actualizarGraficas();
    }

    protected function productsByParent()
    {
        if (!$this->selectedProduct) {
            return;
        }

        $fecha = $this->fechaInicio();


        $productsByP = DB::table('products as p')
            ->leftJoin('sizes as s', 's.id', '=', 'p.size_id')
            ->leftJoin('colors as c', 'c.id', '=', 'p.color_id')
            ->where('p.parent_id', $this->selectedProduct->id)
            ->whereNull('p.deleted_at')
            ->select(
                's.name as size_name',
                'c.name as color_name',
                'p.stock as total'
            )
            ->get();

        $colors = $productsByP
            ->groupBy('color_name')
            ->map(function ($items) {
                return $items->sum('total');
            })
            ->sortDesc()
            ->keys()
            ->values();

        $sizes = $productsByP
            ->pluck('size_name')
            ->unique()
            ->values();


        $series = [];

        foreach ($sizes as $size) {

            $data = [];

            foreach ($colors as $color) {

                $item = $productsByP
                    ->where('size_name', $size)
                    ->where('color_name', $color)
                    ->first();

                $data[] = $item ? $item->total : 0;
            }


            $series[] = [
                'name' => $size,
                'data' => $data
            ];
        }

        $this->productsChart = [
            'categories' => $colors,
            'series' => $series,
        ];

        return $this->productsChart;
    }


protected function materialsConsumed()
{
    if (!$this->selectedProduct) {
        return;
    }

    $fecha = $this->fechaInicio();

    $materials = DB::table('material_orders as mo')
        ->join('product_order as po', 'po.id', '=', 'mo.product_order_id')
        ->join('products as p', 'p.id', '=', 'po.product_id')
        ->join('materials as m', 'm.id', '=', 'mo.material_id')
        ->join('units as u', 'u.id', '=', 'm.unit_id')
        ->where('mo.deleted_at', null)
        ->where('p.parent_id', $this->selectedProduct->id)
        ->whereDate('mo.created_at', '>=', $fecha)
        ->select(
            'm.name',
            'u.name as unit_name',
            'm.part_number as part_number',
            DB::raw('ROUND(SUM(mo.quantity), 1) as total')
        )
        ->groupBy('m.id', 'm.name')
        ->orderByDesc('total')
        ->get();

    $this->materialsChart = [
        'categories' => $materials->map(function ($item) {
            $shortName = collect(explode(' ', $item->name))
                ->map(function ($word) {
                    return mb_substr($word, 0, 3);
                })
                ->implode(' ');


            return $shortName . " ({$item->unit_name}) ({$item->part_number}) ";
        }),
        'series' => [
            [
                'name' => 'Consumido',
                'data' => $materials->pluck('total')
            ]
        ]
    ];

    // dd($this->materialsChart);

    return $this->materialsChart;
}

    protected function productSaleTotal()
    {
        if (!$this->selectedProduct) {
            return;
        }

        $fecha = $this->fechaInicio();

        /*
        |--------------------------------------------------------------------------
        | Ventas
        |--------------------------------------------------------------------------
        */
        $ventas = DB::table('product_order as po')
            ->join('orders as or','or.id','=','po.order_id')
            ->join('products as p', 'p.id', '=', 'po.product_id')
            ->join('products as parent', 'parent.id', '=', 'p.parent_id')
            ->where('p.parent_id', $this->selectedProduct->id)
            ->where('po.deleted_at', null)
            ->where('or.type', true)
            ->where('or.from_store', null)
            ->whereDate('po.created_at', '>=', $fecha)
            ->selectRaw("
                SUM(po.price * po.quantity) as ventas_totales,
                SUM(po.quantity) as productos_vendidos,
                SUM((po.price - parent.cost) * po.quantity) as utilidad_total
            ")
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Inventario
        |--------------------------------------------------------------------------
        */

        $inventario = DB::table('products as hijo')
            ->join('products as padre', 'padre.id', '=', 'hijo.parent_id')
            ->where('hijo.parent_id', $this->selectedProduct->id)
            ->where('hijo.deleted_at', null)
            ->selectRaw("
                SUM(hijo.stock) as inventario_total,
                SUM(hijo.stock * padre.cost) as valor_inventario
            ")
            ->first();
        /*
        |--------------------------------------------------------------------------
        | Subproducto más vendido
        |--------------------------------------------------------------------------
        */
        $subproductoMasVendido = DB::table('product_order as po')
            ->join('orders as or','or.id','=','po.order_id')
            ->join('products as p', 'p.id', '=', 'po.product_id')
            ->leftJoin('sizes as s', 's.id', '=', 'p.size_id')
            ->leftJoin('colors as c', 'c.id', '=', 'p.color_id')
            ->where('p.parent_id', $this->selectedProduct->id)
            ->where('po.deleted_at', null)
            ->where('or.type', true)
            ->where('or.from_store', null)
            ->whereDate('po.created_at', '>=', $fecha)
            ->select(
                'po.product_id',
                DB::raw('SUM(po.quantity) as total_vendido'),
                's.name as size_name',
                'c.name as color_name'
            )
            ->groupBy('po.product_id')
            ->orderByDesc('total_vendido')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Subproducto con mayor inventario
        |--------------------------------------------------------------------------
        */
        $subproductoMayorInventario = DB::table('products as p')
            ->leftJoin('sizes as s', 's.id', '=', 'p.size_id')
            ->leftJoin('colors as c', 'c.id', '=', 'p.color_id')
            ->where('p.parent_id', $this->selectedProduct->id)
            ->where('p.deleted_at', null)
            ->orderByDesc('p.stock')
            ->select(
                'p.*',
                's.name as size_name',
                'c.name as color_name'
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Asignar valores
        |--------------------------------------------------------------------------
        */
        $ventasTotales = $ventas->ventas_totales ?? 0;
        $utilidadTotal = $ventas->utilidad_total ?? 0;

        $this->ventasTotales = $ventasTotales;
        $this->productosVendidos = $ventas->productos_vendidos ?? 0;
        $this->utilidadTotal = $utilidadTotal;

        $this->inventarioTotal = $inventario->inventario_total ?? 0;
        $this->valorInventario = $inventario->valor_inventario ?? 0;

        // dd($subproductoMayorInventario);


        $this->subproductoMasVendido = $subproductoMasVendido
            ? (array) $subproductoMasVendido
            : [];

        $this->subproductoMayorInventario = $subproductoMayorInventario
            ? (array) $subproductoMayorInventario
            : [];


        $this->margenUtilidad = $ventasTotales > 0
            ? ($utilidadTotal / $ventasTotales) * 100
            : 0;
    }

    protected function heatmapProductos()
    {
        if (!$this->selectedProduct) {
            return [];
        }

        $rows = DB::table('product_order as po')
            ->join('orders as or','or.id','=','po.order_id')
            ->join('products as p', 'p.id', '=', 'po.product_id')
            ->select(
                'p.name',
                DB::raw('DATE(po.created_at) as fecha'),
                DB::raw('SUM(po.quantity) as total')
            )
            ->where('or.type', true)
            ->where('or.from_store', null)
            ->where('p.parent_id', $this->selectedProduct->id)
            ->whereDate('po.created_at', '>=', now()->subYear())
            ->groupBy(DB::raw('DATE(po.created_at)'))
            ->orderBy('fecha')
            ->get();

        // Fecha => total
        $ventas = [];

        foreach ($rows as $row) {
            $ventas[$row->fecha] = (int)$row->total;
        }

        $dias = [
            'Dom',
            'Lun',
            'Mar',
            'Mie',
            'Jue',
            'Vie',
            'Sab',
        ];

        $series = [];

        foreach ($dias as $dia) {

            $series[] = [
                'name' => $dia,
                'data' => []
            ];
        }

        $inicio = Carbon::parse(now()->subYear())->startOfWeek(Carbon::SUNDAY);
        $fin = now()->endOfDay();

        while ($inicio <= $fin) {

            $dow = $inicio->dayOfWeek;

            $series[$dow]['data'][] = [

                // columna = domingo de esa semana
                'x' => $inicio->copy()->startOfWeek(Carbon::SUNDAY)->timestamp * 1000,
                // 'x' => $inicio->timestamp * 1000,

                // color
                'y' => $ventas[$inicio->format('Y-m-d')] ?? 0,

                // tooltip
                'date' => $inicio->timestamp * 1000

            ];

            $inicio->addDay();
        }

        // Apex dibuja la última serie arriba
        return array_reverse($series);
    }

    protected function actualizarGraficas()
    {
        $this->emit('heatmapProductos', $this->heatmapProductos());
        $this->productSaleTotal();
        $this->emit('productsByParent', $this->productsByParent(), $this->selectedProduct->name.' | '.$this->selectedProduct->code);
        $this->emit('materialsConsumed', $this->materialsConsumed(), $this->selectedProduct->name.' | '.$this->selectedProduct->code);

    }

    public function render()
    {
        return view('backend.charts.graph-by-product');
    }
}

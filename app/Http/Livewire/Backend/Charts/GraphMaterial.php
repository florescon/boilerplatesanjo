<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Material;
use App\Models\Consumption;
use Carbon\Carbon;

class GraphMaterial extends Component
{
    public $periodo = '30days';

    public $query;

    public $heatmap = [];

    public $graphMaterial = [];

    public $selectedMaterial = null;

    public $consumptionQ = 0;
    public $consumptionP = 0;


    public $currentStock = 0;       // Stock actual
    public $unitCost = 0;           // Costo unitario
    public $inventoryValue = 0;     // Valor del inventario
    public $lastPurchase = 0;       // Fecha de última compra
    public $priceVariation = 0;    // % variación de precio

    public $historiesQ = 0;
    public $historiesP = 0;

    protected $queryString = [
        'periodo' => ['except' => '30days'],
    ];

    public function mount()
    {
        // $this->heatmap = $this->heatmapProductos();
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

    public function updatedQuery()
    {
        $this->products = Material::with('family', 'color')
            ->whereRaw("part_number LIKE \"%$this->query%\"")
            ->orWhereRaw("name LIKE \"%$this->query%\"")
            ->get()->take(12)
            ->toArray();

       // $this->selectedMaterial = null;
    }

    public function reset_search()
    {
        $this->query = '';
        $this->products = [];
        $this->selectedMaterial = null;
    }

    public function selectProduct($idProduct)
    {
        $product = Material::with('family', 'color', 'size')->findOrFail($idProduct);

        if ($product) {

            $this->selectedMaterial = Material::with('family', 'color', 'size', 'unit')->findOrFail($idProduct);
            $this->query = '';
        }

        $this->actualizarGraficas();

    }

protected function lineMaterials()
{
    if (!$this->selectedMaterial) {
        return [
            'categories' => [],
            'series' => [],
        ];
    }

    $fecha = $this->fechaInicio();

    /*
    |--------------------------------------------------------------------------
    | Material Orders
    |--------------------------------------------------------------------------
    */

    $orders = DB::table('material_orders as mo')
        ->join('orders as or', 'or.id', '=', 'mo.order_id')
        ->join('materials as m', 'm.id', '=', 'mo.material_id')
        ->select(
            DB::raw('DATE(mo.created_at) as fecha'),
            DB::raw('ROUND(SUM(mo.quantity), 1) as total')
        )
        ->where('or.type', true)
        ->whereNull('or.from_store')
        ->whereNull('mo.deleted_at')
        ->where('m.id', $this->selectedMaterial->id)
        ->whereDate('mo.created_at', '>=', $fecha)
        ->groupBy(DB::raw('DATE(mo.created_at)'))
        ->orderBy('fecha')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Historial de movimientos
    |--------------------------------------------------------------------------
    */

    $histories = DB::table('material_histories as mh')
        ->select(
            DB::raw('DATE(mh.created_at) as fecha'),
            DB::raw('ROUND(SUM(
                CASE
                    WHEN mh.stock > 0 THEN mh.stock
                    ELSE 0
                END
            ), 1) as total')
        )
        ->where('mh.material_id', $this->selectedMaterial->id)
        ->whereNull('mh.deleted_at')
        ->whereDate('mh.created_at', '>=', $fecha)
        ->groupBy(DB::raw('DATE(mh.created_at)'))
        ->orderBy('fecha')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Unificar fechas
    |--------------------------------------------------------------------------
    */

    $categories = collect()
        ->merge($orders->pluck('fecha'))
        ->merge($histories->pluck('fecha'))
        ->unique()
        ->sort()
        ->values();


    /*
    |--------------------------------------------------------------------------
    | Series
    |--------------------------------------------------------------------------
    */

    $ordersData = $orders->keyBy('fecha');

    $historiesData = $histories->keyBy('fecha');


    return [
        'categories' => $categories->toArray(),

        'series' => [

            [
                'name' => 'Consumido',

                'data' => $categories
                    ->map(function ($fecha) use ($ordersData) {
                        return isset($ordersData[$fecha])
                            ? (float) $ordersData[$fecha]->total
                            : 0;
                    })
                    ->toArray(),
            ],

            [
                'name' => 'Compras',

                'data' => $categories
                    ->map(function ($fecha) use ($historiesData) {
                        return isset($historiesData[$fecha])
                            ? (float) $historiesData[$fecha]->total
                            : 0;
                    })
                    ->toArray(),
            ],

        ],
    ];
}
    protected function heatmapMaterials()
    {
        if (!$this->selectedMaterial) {
            return [];
        }

        $rows = DB::table('material_orders as mo')
            ->join('orders as or','or.id','=','mo.order_id')
            ->join('materials as m', 'm.id', '=', 'mo.material_id')
            ->select(
                'm.name',
                DB::raw('DATE(mo.created_at) as fecha'),
                DB::raw('SUM(mo.quantity) as total')
            )
            ->where('or.type', true)
            ->where('or.from_store', null)
            ->where('mo.deleted_at', null)
            ->where('m.id', $this->selectedMaterial->id)
            ->whereDate('mo.created_at', '>=', now()->subYear())
            ->groupBy(DB::raw('DATE(mo.created_at)'))
            ->orderBy('fecha')
            ->get();

        // dd($rows);
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

        // dd($series);
        // Apex dibuja la última serie arriba
        return array_reverse($series);
    }


    protected function actualizarGraficas()
    {
        // dd($this->graphMaterial());
        // $this->emit('graphMaterial', $this->graphMaterial());
        $this->emit('graphMaterial', $this->getSankeyData());
        $this->emit('heatmapMaterials', $this->heatmapMaterials());
        $this->emit('lineMaterials', $this->lineMaterials());

        $this->productSaleTotal();
    }

protected function productSaleTotal()
{
    if (!$this->selectedMaterial) {
        return;
    }

    $fecha = $this->fechaInicio();

    /*
    |--------------------------------------------------------------------------
    | Consumo de materiales en órdenes
    |--------------------------------------------------------------------------
    */

    $material_orders = DB::table('material_orders as mo')
        ->join('orders as or', 'or.id', '=', 'mo.order_id')
        ->where('mo.material_id', $this->selectedMaterial->id)
        ->whereNull('mo.deleted_at')
        ->whereDate('mo.created_at', '>=', $fecha)
        ->selectRaw("
            SUM(mo.quantity) as consumption_q,
            SUM(mo.quantity * mo.price) as consumption_p
        ")
        ->first();

    $this->consumptionQ = $material_orders->consumption_q ?? 0;
    $this->consumptionP = $material_orders->consumption_p ?? 0;


    /*
    |--------------------------------------------------------------------------
    | Historial de movimientos
    |--------------------------------------------------------------------------
    */

    $material_histories = DB::table('material_histories as mo')
        ->where('mo.material_id', $this->selectedMaterial->id)
        ->whereNull('mo.deleted_at')
        ->whereDate('mo.created_at', '>=', $fecha)
        ->selectRaw("
            SUM(CASE WHEN mo.stock > 0 THEN mo.stock ELSE 0 END) as consumption_q,
            SUM(CASE WHEN mo.stock > 0 THEN mo.stock * mo.price ELSE 0 END) as consumption_p
        ")
        ->first();

    $this->historiesQ = $material_histories->consumption_q ?? 0;
    $this->historiesP = $material_histories->consumption_p ?? 0;


    /*
    |--------------------------------------------------------------------------
    | Datos actuales del material
    |--------------------------------------------------------------------------
    */

    $material = DB::table('materials')
        ->where('id', $this->selectedMaterial->id)
        ->first();

    // Stock actual
    $this->currentStock = $material->stock ?? 0;

    // Costo unitario
    $this->unitCost = $material->price ?? 0;

    // Valor del inventario
    $this->inventoryValue = $this->currentStock * $this->unitCost;


    /*
    |--------------------------------------------------------------------------
    | Última compra
    |--------------------------------------------------------------------------
    */

    $lastPurchase = DB::table('material_histories')
        ->where('material_id', $this->selectedMaterial->id)
        ->whereNull('deleted_at')
        ->whereNotNull('invoice')
        ->latest('created_at')
        ->first();


    $this->lastPurchase = $lastPurchase
        ? \Carbon\Carbon::parse($lastPurchase->created_at)->format('d-m-y')
        : null;

    /*
    |--------------------------------------------------------------------------
    | Variación de precio
    |--------------------------------------------------------------------------
    */

    if (
        $lastPurchase &&
        $lastPurchase->old_price !== null &&
        $lastPurchase->old_price != 0
    ) {
        $this->priceVariation = (
            ($lastPurchase->price - $lastPurchase->old_price)
            / $lastPurchase->old_price
        ) * 100;
    } else {
        $this->priceVariation = 0;
    }
}

public function getSankeyData()
{
    $fecha = now()->subDays(15);

    $data = DB::table('material_orders as mo')
        ->join('product_order as po', 'po.id', '=', 'mo.product_order_id')
        ->join('products as p', 'p.id', '=', 'po.product_id')
        ->join('products as parent', 'parent.id', '=', 'p.parent_id')
        ->join('colors as c', 'c.id', '=', 'p.color_id')
        ->join('materials as m', 'm.id', '=', 'mo.material_id')
        ->join('families as f', 'f.id', '=', 'm.family_id')
        ->select([
            'mo.order_id',

            'p.parent_id',
            'parent.code as parent_code',
            'parent.name as parent_name',

            'p.color_id',
            'c.name as color_name',

            'f.id as family_id',
            'f.name as family_name',

            'm.id as material_id',
            'm.name as material_name',

            DB::raw('ROUND(SUM(po.quantity), 1) as quantity_order'),
            DB::raw('ROUND(SUM(mo.quantity), 1) as quantity_material'),
        ])
        ->where('mo.order_id', 3410)
        ->where('mo.deleted_at', null)
        ->where('po.deleted_at', null)
        // ->whereDate('mo.created_at', '>=', $fecha)
        ->groupBy([
            'mo.order_id',

            'p.parent_id',
            'parent.code',
            'parent.name',

            'p.color_id',
            'c.name',

            'f.id',
            'f.name',

            'm.id',
            'm.name',
        ])
        ->get();


    $nodes = [];
    $edges = [];


    /*
    |--------------------------------------------------------------------------
    | Función para crear/agrupar edges
    |--------------------------------------------------------------------------
    */

    $addEdge = function ($source, $target, $value) use (&$edges) {

        $key = $source . '|' . $target;

        if (!isset($edges[$key])) {

            $edges[$key] = [
                'source' => $source,
                'target' => $target,
                'value' => 0,
            ];
        }

        $edges[$key]['value'] += (float) $value;
    };


    foreach ($data as $row) {

        /*
        |--------------------------------------------------------------------------
        | IDs
        |--------------------------------------------------------------------------
        */

        $orderId = 'order_' . $row->order_id;

        $parentId = 'parent_' . $row->parent_id;

        $colorId = 'color_' . $row->parent_id . '_' . $row->color_id;

        $familyId = 'family_' . $row->family_id;

        $materialId = 'material_' . $row->material_id;


        /*
        |--------------------------------------------------------------------------
        | NODO ORDEN
        |--------------------------------------------------------------------------
        */

        $nodes[$orderId] = [
            'id' => $orderId,
            'title' => 'Orden #' . $row->order_id,
            'color' => '#FF7F50',
        ];


        /*
        |--------------------------------------------------------------------------
        | NODO PRODUCTO PADRE
        |--------------------------------------------------------------------------
        */

        $nodes[$parentId] = [
            'id' => $parentId,
            'title' => $row->parent_code,
            'color' => '#6366F1',
        ];


        /*
        |--------------------------------------------------------------------------
        | NODO COLOR
        |--------------------------------------------------------------------------
        */

        $nodes[$colorId] = [
            'id' => $colorId,
            'title' => $row->color_name,
            'color' => '#EC4899',
        ];


        /*
        |--------------------------------------------------------------------------
        | NODO FAMILIA
        |--------------------------------------------------------------------------
        */

        $nodes[$familyId] = [
            'id' => $familyId,
            'title' => $row->family_name,
            'color' => '#F97316',
        ];


        /*
        |--------------------------------------------------------------------------
        | NODO MATERIAL
        |--------------------------------------------------------------------------
        */

        $nodes[$materialId] = [
            'id' => $materialId,
            'title' => $row->material_name,
            'color' => '#10B981',
        ];


        /*
        |--------------------------------------------------------------------------
        | ORDEN → PRODUCTO PADRE
        |--------------------------------------------------------------------------
        */

        $addEdge(
            $orderId,
            $parentId,
            $row->quantity_order
        );


        /*
        |--------------------------------------------------------------------------
        | PRODUCTO PADRE → COLOR
        |--------------------------------------------------------------------------
        */

        $addEdge(
            $parentId,
            $colorId,
            $row->quantity_order
        );


        /*
        |--------------------------------------------------------------------------
        | COLOR → FAMILIA
        |--------------------------------------------------------------------------
        */

        $addEdge(
            $colorId,
            $familyId,
            $row->quantity_material
        );


        /*
        |--------------------------------------------------------------------------
        | FAMILIA → MATERIAL
        |--------------------------------------------------------------------------
        */

        $addEdge(
            $familyId,
            $materialId,
            $row->quantity_material
        );
    }


    return [
        'nodes' => array_values($nodes),
        'edges' => array_values($edges),
    ];
}

    public function graphMaterial()
    {
        if (!$this->selectedMaterial) {
            return [];
        }

    // $consumptions = DB::table('consumptions as cons')
    //     ->join('materials as m', 'm.id', '=', 'cons.material_id')
    //     ->join('families as f', 'f.id', '=', 'm.family_id')
    //     ->join('products as p', 'p.id', '=', 'cons.product_id')
    //     ->join('products as parent_products', 'parent_products.id', '=', 'p.parent_id')
    //     ->select(
    //         'f.name as family',
    //         'm.name as material',
    //         'parent_products.name as product_name',
    //         DB::raw('SUM(cons.quantity) as quantity')
    //     )
    //     ->groupBy(
    //         'f.name',
    //         'm.name',
    //         'parent_products.name'
    //     )
    //     ->get();



    $consumptions = DB::table('consumptions as cons')
        ->where('cons.product_id', $this->selectedMaterial->id)
        ->join('materials as m', 'm.id', '=', 'cons.material_id')
        ->join('families as f', 'f.id', '=', 'm.family_id')
        ->join('products as p', 'p.id', '=', 'cons.product_id')
        ->leftJoin('products as parent_products', 'parent_products.id', '=', 'p.parent_id')
        ->select(
            'f.name as family',
            'm.name as material',
            'p.name as product',
            'cons.color_id',
            'cons.size_id',
            'cons.puntual',
            DB::raw('CAST(ROUND(SUM(cons.quantity), 2) AS DECIMAL(10,2)) as quantity')
        )
        ->groupBy(
            'f.name',
            'm.name',
            'p.name',
            'cons.color_id',
            'cons.size_id',
            'cons.puntual'
        )
        ->get();


        $nodes = [];
        $edges = [];


        foreach ($consumptions as $row) {

            // Familia
            $nodes[$row->family] = [
                'id' => $row->family,
                'title' => $row->family,
                'color' => '#6366F1'
            ];


            // Material
            $nodes[$row->material] = [
                'id' => $row->material,
                'title' => $row->material,
                'color' => '#10B981'
            ];


            // Producto
            $nodes[$row->product] = [
                'id' => $row->product,
                'title' => $row->product,
                'color' => '#F97316'
            ];


            // Familia -> Material
            $edges[] = [
                'source' => $row->family,
                'target' => $row->material,
                'value' => (float)$row->quantity
            ];


            // Material -> Producto
            $edges[] = [
                'source' => $row->material,
                'target' => $row->product,
                'value' => (float)$row->quantity
            ];
        }

        return [
            'nodes' => array_values($nodes),
            'edges' => $edges
        ];
    }
    public function render()
    {

        // dump($this->graphMaterial());
        // dd($this->graphMaterial());
        
        return view('backend.charts.graph-material');
    }
}

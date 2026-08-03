<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Carbon\Carbon;

class GraphByProduct extends Component
{
    public $periodo = '30days';

    public $query;

    public $heatmap = [];

    public $ventasTotales = 0;
    public $productosVendidos = 0;
    public $utilidadTotal = 0;
    public $margenUtilidad = 0;

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
            ->onlyProductsAndServices()
            ->get()->take(6)
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

    protected function productSaleTotal()
    {
        if (!$this->selectedProduct) {
            return;
        }

        $fecha = $this->fechaInicio();

        $data = DB::table('product_order as po')
            ->join('products as p', 'p.id', '=', 'po.product_id')
            ->join('products as parent', 'parent.id', '=', 'p.parent_id')
            ->where('p.parent_id', $this->selectedProduct->id)
            ->whereDate('po.created_at', '>=', $fecha)
            ->selectRaw('
                SUM(po.price * po.quantity) as ventas_totales,
                SUM(po.quantity) as productos_vendidos,
                SUM((po.price - parent.cost) * po.quantity) as utilidad_total
            ')
            ->first();

        $ventas = $data->ventas_totales ?? 0;
        $utilidad = $data->utilidad_total ?? 0;

        $this->ventasTotales = $ventas;
        $this->productosVendidos = $data->productos_vendidos ?? 0;
        $this->utilidadTotal = $utilidad;

        $this->margenUtilidad = $ventas > 0
            ? ($utilidad / $ventas) * 100
            : 0;
    }

    protected function heatmapProductos()
    {
        if (!$this->selectedProduct) {
            return [];
        }

        $rows = DB::table('product_order as po')
            ->join('products as p', 'p.id', '=', 'po.product_id')
            ->select(
                'p.name',
                DB::raw('DATE(po.created_at) as fecha'),
                DB::raw('SUM(po.quantity) as total')
            )
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
    }

    public function render()
    {
        return view('backend.charts.graph-by-product');
    }
}

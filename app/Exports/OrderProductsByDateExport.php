<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\BeforeExport;
use Carbon\Carbon;

class OrderProductsByDateExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithDrawings, WithCustomStartCell, WithEvents
{
    protected $dateInput;
    protected $dateOutput;
    protected $products;

    protected $isProduct;
    protected $isService;
    protected $isStore;
    protected $isGrouped;

    // Recibe los datos en el constructor
    public function __construct($dateInput, $dateOutput, ?bool $isProduct = false, ?bool $isService = false, ?bool $isStore = false, ?bool $isGrouped = false)
    {
        $this->dateInput = $dateInput;
        $this->dateOutput = $dateOutput;
        $this->isProduct = $isProduct;
        $this->isService = $isService;
        $this->isStore = $isStore;
        $this->isGrouped = $isGrouped;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('5')->getFont()->setBold(true);
        $sheet->getStyle('2')->getFont()->setBold(true);
        $sheet->setAutoFilter('A5:I5');
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('SJU');
        $drawing->setPath(public_path('/img/logo2.png'));
        $drawing->setHeight(70);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function beforeExport(BeforeExport $event)
    {
        // Obtener la hoja activa
        $event->getWriter()->getActiveSheet()->setCellValue('A1', 'Text');
    }

    /**
     * Eventos para modificar la hoja
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $products = $this->isProduct ? 'Productos' : '';
                $services = $this->isService ? 'Servicios' : '';
                $dateInput =  $this->dateInput ? Carbon::parse($this->dateInput)->format('d/m/Y') : '';
                $dateOutput = $this->dateOutput ?  Carbon::parse($this->dateOutput)->format('d/m/Y') : '';

                $titulo = "Reporte de $products $services de: {$dateInput} a {$dateOutput}";
                $event->sheet->setCellValue('C2', $titulo);                

                // Aquí puedes agregar más personalizaciones a la hoja después de generar los datos
            },
        ];
    }
    public function headings(): array
    {
        if($this->isGrouped){
            $headings = [
                __('Code'),
                __('Quantity'),
                __('Details'),
                __('Name'),
                __('Provider'),
            ];
        }
        else{
            $headings = [
                __('Code'),
                __('Quantity'),
                __('Name'),
                __('Size_'),
                __('Color'),
                __('Order'),
                __('Customer'),
                __('Provider'),
                __('Date'),
            ];
        }

        return $headings;

    }

    /**
    * @var Invoice $product
    */
public function map($product): array
{
    // Si está agrupado, usar los datos agrupados
    if ($this->isGrouped) {
        return [
            $product['productParentCode'],
            $product['totalQuantity'],
            $product['productColorName'],
            $product['productParentName'],
            $product['productProvider'],
        ];
    }

    // Si no está agrupado, usar los datos individuales
    return [
        $product['productParentCode'],
        $product['productQuantity'],
        $product['productParentName'],
        $product['productSizeName'],
        $product['productColorName'],
        $product['productOrder'],
        $product['productCustomer'],
        $product['productProvider'],
        $product['productDate'],
    ];
}

    /**
    * @return \Illuminate\Support\Collection
    */
public function collection()
{
    $productsCollection = collect();

    $query = Order::whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59'])
                  ->with('products', 'user.customer');

    // Aplicar onlyFromStore() o outFromStore() según el valor de $this->isStore
    if ($this->isStore) {
        $query->onlyRequests()->onlyFromStore(); // Aplicar onlyFromStore() si $this->isStore es true
    } else {
        $query->onlyOrders()->outFromStore(); // Aplicar outFromStore() si $this->isStore es false
    }

    $ordersJson = $query->get(); // Obtener los resultados

    // Recorrer las órdenes y productos para construir la colección
    foreach ($ordersJson as $order) {
        foreach ($order->products as $product_order) {
            if ($this->isProduct && $product_order->product->isProduct()) {
                $productsCollection->push([
                    'productId' => $product_order->id,
                    'productParentId' => $product_order->product->parent_id ?? $product_order->product_id,
                    'productParentCode' => $product_order->product->parent_code ?? null,
                    'productParentName' => $product_order->product->only_name ?? null,
                    'productColor' => $product_order->product->color_id,
                    'productColorName' => $product_order->product->color->name ?? '',
                    'productSizeName' => $product_order->product->size->name ?? '',
                    'productSizeSort' => $product_order->product->size->sort ?? '',
                    'productQuantity' => $product_order->quantity,
                    'productOrder' => $order->folio_or_id_clear,
                    'productCustomer' => optional($order->user)->name,
                    'productDate' => $order->date_for_humans,
                    'productProvider' => $product_order->product->parent->vendor_id ? $product_order->product->parent->vendor->short_name_label : null,
                ]);
            }

            if ($this->isService && !$product_order->product->isProduct()) {
                $productsCollection->push([
                    'productId' => $product_order->id,
                    'productParentId' => $product_order->product->parent_id ?? $product_order->product_id,
                    'productParentCode' => $product_order->product->parent_code ?? null,
                    'productParentName' => $product_order->product->only_name ?? null,
                    'productColor' => $product_order->product->color_id,
                    'productColorName' => $product_order->product->color->name ?? '',
                    'productSizeName' => $product_order->product->size->name ?? '',
                    'productSizeSort' => $product_order->product->size->sort ?? '',
                    'productQuantity' => $product_order->quantity,
                    'productOrder' => $order->folio_or_id_clear,
                    'productCustomer' => optional($order->user)->name,
                    'productDate' => $order->date_for_humans,
                    'productProvider' => null,
                ]);
            }
        }
    }

    $this->products = $productsCollection;

    // Si $isGrouped es true, agrupar los productos
    if ($this->isGrouped) {
        $grouped = $this->products->groupBy(function ($item) {
            return $item['productParentId'] . '-' . $item['productColor']; // Combinamos ambos valores para agrupar
        })->map(function ($group) {
            return [
                'productParentCode' => $group->first()['productParentCode'],
                'productColorName' => $group->first()['productColorName'],
                'productSizeName' => $group->first()['productSizeName'],
                'productParentName' => $group->first()['productParentName'],
                'totalQuantity' => $group->sum('productQuantity'),

                'productOrder' => $group->first()['productOrder'],
                'productCustomer' => $group->first()['productCustomer'],
                'productDate' => $group->first()['productDate'],

            ];
        });

        return $grouped;
    }
    else{
    // Después de construir la colección $productsCollection, aplicamos el ordenamiento
        $this->products = $productsCollection->sortBy([
            ['productParentName', 'asc'],
            ['productColor', 'asc'],
            ['productSizeSort', 'asc'],
        ]);        
    }

    // Si $isGrouped es false, devolver la colección sin agrupar
    return $this->products;
}
}

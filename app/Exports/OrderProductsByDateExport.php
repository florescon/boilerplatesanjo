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

class OrderProductsByDateExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithDrawings, WithCustomStartCell, WithEvents
{
    protected $dateInput;
    protected $dateOutput;
    protected $products;

    protected $isProduct;
    protected $isService;

    // Recibe los datos en el constructor
    public function __construct($dateInput, $dateOutput, $isProduct, $isService)
    {
        $this->dateInput = $dateInput;
        $this->dateOutput = $dateOutput;
        $this->isProduct = $isProduct;
        $this->isService = $isService;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('8')->getFont()->setBold(true);
        $sheet->setAutoFilter('A8:D8');
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('SJU');
        $drawing->setPath(public_path('/img/logo2.png'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('A2');

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
                // Aquí puedes agregar más personalizaciones a la hoja después de generar los datos
            },
        ];
    }
    public function headings(): array
    {
       $headings = [
            __('Quantity'),
            __('Code'),
            __('Details'),
            __('Name'),
        ];

        return $headings;

    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        // Mapea los datos para la exportación
        return [
            $product['totalQuantity'],
            $product['productParentCode'],
            $product['productColorName'],
            $product['productParentName'],
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $ordercollection = collect();
        $productsCollection = collect();

        $ordersJson = Order::whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59'])
                            ->onlyOrders()->outFromStore()
                            ->with('products', 'user.customer')
                            ->get();

        foreach ($ordersJson as $order) {
            foreach ($order->products as $product_order) {
                if($this->isProduct && $product_order->product->isProduct()){

                    $productsCollection->push([
                        'productId' => $product_order->id,
                        'productParentId' => $product_order->product->parent_id ?? $product_order->product_id,
                        'productParentCode' => $product_order->product->parent_code ?? null,
                        'productParentName' => $product_order->product->only_name ?? null,
                        'productColor' => $product_order->product->color_id,
                        'productColorName' => $product_order->product->color->name ?? '',
                        'productQuantity' => $product_order->quantity,
                    ]);
                }

                if($this->isService && !$product_order->product->isProduct()){

                    $productsCollection->push([
                        'productId' => $product_order->id,
                        'productParentId' => $product_order->product->parent_id ?? $product_order->product_id,
                        'productParentCode' => $product_order->product->parent_code ?? null,
                        'productParentName' => $product_order->product->only_name ?? null,
                        'productColor' => $product_order->product->color_id,
                        'productColorName' => $product_order->product->color->name ?? '',
                        'productQuantity' => $product_order->quantity,
                    ]);
                }
            }
        }

        $this->products = $productsCollection;

        $grouped = $this->products->groupBy(function ($item) {
            return $item['productParentId'] . '-' . $item['productColor']; // Combinamos ambos valores para agrupar
        })->map(function ($group) {
            return [
                'productParentCode' => $group->first()['productParentCode'], // Obtener el nombre del primer producto del grupo
                'productColorName' => $group->first()['productColorName'], // Obtener el nombre del color del primer producto del grupo
                'productParentName' => $group->first()['productParentName'], // Obtener el nombre del primer producto del grupo
                'totalQuantity' => $group->sum('productQuantity'), // Sumar la cantidad de productos en el grupo
            ];
        });

        return $grouped; 
    }

}
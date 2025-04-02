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

class OrderProductsReportExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithDrawings, WithCustomStartCell, WithEvents
{
    protected $dateInput;
    protected $dateOutput;
    protected $products;

    protected $isProduct;
    protected $isService;
    protected $isStore;

    // Recibe los datos en el constructor
    public function __construct($dateInput, $dateOutput, $isProduct, $isService, ?bool $isStore = false)
    {
        $this->dateInput = $dateInput;
        $this->dateOutput = $dateOutput;
        $this->isProduct = $isProduct;
        $this->isService = $isService;
        $this->isStore = $isStore;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('8')->getFont()->setBold(true);
        $sheet->setAutoFilter('A8:F8');
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
                $event->sheet->setCellValue('A5', $titulo);                
                // Aquí puedes agregar más personalizaciones a la hoja después de generar los datos
            },
        ];
    }
    public function headings(): array
    {
       $headings = [
            __('Quantity'),
            __('Code'),
            __('Color'),
            __('Size_'),
            __('Name'),
            __('Order'),
            __('Customer'),
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
            $product['productQuantity'],  // Asumí que 'totalQuantity' es 'productQuantity' directamente aquí
            $product['productParentCode'],
            !$this->isService ? $product['productColorName'] : 'N/A',
            !$this->isService ? $product['productSizeName'] : 'N/A',
            $product['productParentName'],
            $product['productOrder'],
            $product['productCustomer'],
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $ordercollection = collect();
        $productsCollection = collect();

        $query = Order::whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59'])
                            ->with('products', 'user.customer');

        if ($this->isStore) {
            $query->onlyRequests()->onlyFromStore(); // Aplicar onlyFromStore() si $this->isStore es true
        } else {
            $query->onlyOrders()->outFromStore(); // Aplicar outFromStore() si $this->isStore es false
        }

        $ordersJson = $query->get(); // Obtener los resultados

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
                        'productSizeName' => $product_order->product->size->name ?? '',
                        'productQuantity' => $product_order->quantity,
                        'productOrder' => $order->folio_or_id_clear,
                        'productCustomer' => $order->user_name_clear,
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
                        'productSizeName' => $product_order->product->size->name ?? '',
                        'productQuantity' => $product_order->quantity,
                        'productOrder' => $order->folio_or_id_clear,
                        'productCustomer' => $order->user_name_clear,
                    ]);
                }
            }
        }

        $this->products = $productsCollection;

        // Eliminamos la agrupación, solo devolvemos los productos
        return $this->products;
    }

}

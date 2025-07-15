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

class OrderProcessExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithDrawings, WithCustomStartCell, WithEvents
{

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('5')->getFont()->setBold(true);
        $sheet->getStyle('2')->getFont()->setBold(true);
        $sheet->setAutoFilter('A5:T5');
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
            // Obtener la fecha y hora actual con el formato deseado
            $fechaHora = now()->format('d/m/Y, H:i');
            
            // Establecer el valor en la celda C1
            $event->sheet->setCellValue('C1', 'Fecha creado: ' . $fechaHora);
            
            $titulo = "Reporte de Pedidos en Captura y Proceso";
            $sub = "Nota: Los valores corresponden a Captura y Proceso de cada Estación, a excepción de 'Salida', que es el valor asignado en esa Estación.";
            $event->sheet->setCellValue('C2', $titulo);                
            $event->sheet->setCellValue('C3', $sub);                
            // Aquí puedes agregar más personalizaciones a la hoja después de generar los datos
        },
    ];
}

    public function headings(): array
    {
       $headings = [
            __('Created'),
            __('Order'),
            __('Customer'),
            __('Comment'),
            __('Products'),
            __('Services'),
            __('Captura'),
            __('Corte'),
            __('Confeccion'),
            __('Calidad'),
            __('Proveedor'),
            __('Conformado'),
            __('Personalizacion'),
            __('Embarque'),
            __('Output'),
            __('Quotation'),
            __('Request n.º'),
            __('Purchase Order'),
            __('Info customer'),
            __('Observations'),
        ];

        return $headings;
    }


    /**
    * @var Order $order
    */
    public function map($order): array
    {
        $productionData = $order->getTotalGraphicWorkAttribute();

        $outs = $order->getTotalBatchOnlyStatusProduction(15);

        $data = [
            $order->date_for_humans,
            $order->folio,
            $order->user_name_clear,
            $order->comment,
            $order->total_products_by_all_products,
            $order->total_products_by_all_services,
            $productionData['collection']['captura'] ?? 0,
            $productionData['collection']['corte'] ?? 0,
            $productionData['collection']['confeccion'] ?? 0,
            $productionData['collection']['calidad'] ?? 0,
            $productionData['collection']['proveedor'] ?? 0,
            $productionData['collection']['conformado'] ?? 0,
            $productionData['collection']['personalizacion'] ?? 0,
            $productionData['collection']['embarque'] ?? 0,
            $outs ?? 0,
            $order->quotation,
            $order->request,
            $order->purchase,
            $order->info_customer,
            $order->observation,
        ];

        return $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $orders = Order::with('products.product', 'user')
            ->doesntHave('stations') // <- Solo órdenes con al menos una estación
            ->where(function($q) {
                        $q->whereRaw("(
                            SELECT COALESCE(SUM(pbi.input_quantity), 0) 
                            FROM production_batch_items pbi
                            JOIN production_batches pb ON pb.id = pbi.batch_id
                            WHERE pb.order_id = orders.id
                            AND pbi.is_principal = 1
                            AND pbi.with_previous IS NULL
                        ) != (
                            SELECT COALESCE(SUM(po.quantity), 0)
                            FROM product_order po
                            JOIN products p ON p.id = po.product_id
                            WHERE po.order_id = orders.id
                            AND p.type = 1
                        )")
                        ->orWhereRaw("EXISTS (
                            SELECT 1 
                            FROM production_batch_items pbi
                            JOIN production_batches pb ON pb.id = pbi.batch_id
                            WHERE pb.order_id = orders.id
                            AND pbi.active != 0
                        )");
                    })
            ->onlyOrders()
            ->outFromStore()
            ->flowchart()
            ->orderBy('folio')
            ->get();

        $orders->each->getTotalGraphicWorkAttribute();

        return $orders;
    }
}

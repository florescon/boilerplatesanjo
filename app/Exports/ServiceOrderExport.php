<?php

namespace App\Exports;

use App\Models\ServiceOrder;
use App\Models\ServiceType;
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

class ServiceOrderExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithDrawings, WithCustomStartCell, WithEvents
{
    protected $dateInput;
    protected $dateOutput;
    protected $products;

    protected $serviceType;
    protected $isGrouped;

    // Recibe los datos en el constructor
    public function __construct($dateInput, $dateOutput, ?int $serviceType = null, ?bool $isGrouped = false)
    {
        $this->dateInput = $dateInput;
        $this->dateOutput = $dateOutput;
        $this->serviceType = $serviceType;
        $this->isGrouped = $isGrouped;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('8')->getFont()->setBold(true);
        $sheet->setAutoFilter('A8:I8');
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
                $serviceType = $this->serviceType ? ServiceType::whereId($this->serviceType)->first() : '';
                $serviceName = $serviceType ? $serviceType->name : '';
                $dateInput =  $this->dateInput ? Carbon::parse($this->dateInput)->format('d/m/Y') : '';
                $dateOutput = $this->dateOutput ?  Carbon::parse($this->dateOutput)->format('d/m/Y') : '';

                $titulo = "Reporte de Ordenes de Servicio - $serviceName de: {$dateInput} a {$dateOutput}";
                $event->sheet->setCellValue('A5', $titulo);                

                // Aquí puedes agregar más personalizaciones a la hoja después de generar los datos
            },
        ];
    }

    public function headings(): array
    {
       $headings = [
            __('Folio'),
            __('Order'),
            __('User'),
            __('Service Type'),
            __('Comment'),
            __('Dimensions'),
            __('Text'),
            __('Created by'),
            __('Created'),
            __('Total'),
        ];

        return $headings;

    }

    /**
    * @var Invoice $serviceOrder
    */
public function map($serviceOrder): array
{
    // Si está agrupado, usar los datos agrupados
    if ($this->isGrouped) {
        return [
            '', // Folio (vacío porque no aplica en agrupación)
            '', // Order (vacío porque no aplica en agrupación)
            $serviceOrder['personal']->name ?? '', // User
            $serviceOrder['service_type']->name ?? '', // Service Type
            '', // Comment (vacío porque no aplica en agrupación)
            '', // Dimensions (vacío porque no aplica en agrupación)
            '', // Text (vacío porque no aplica en agrupación)
            $serviceOrder['createdby']->name ?? '', // Created by
            $serviceOrder['created_at']->format('d-m-Y'), // Created
            $serviceOrder['total_products'], // Total
        ];
    }

    // Si no está agrupado, usar los datos normales
    return [
        $serviceOrder->id,
        $serviceOrder->order_id ? optional($serviceOrder->order)->folio_or_id_clear : '',
        $serviceOrder->user_id ? optional($serviceOrder->personal)->name : '',
        $serviceOrder->service_type ? optional($serviceOrder->service_type)->name : '',
        $serviceOrder->comment,
        $serviceOrder->dimensions,
        $serviceOrder->file_text,
        $serviceOrder->created_id ? optional($serviceOrder->createdby)->name : '',
        $serviceOrder->created_at->format('d-m-Y'),
        $serviceOrder->total_products,
    ];
}
    /**
    * @return \Illuminate\Support\Collection
    */
public function collection()
{
    $query = ServiceOrder::whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59'])
                        ->with('order', 'service_type', 'createdby', 'personal', 'product_service_orders');

    // Aplicar el filtro solo si $this->serviceType no es null
    if ($this->serviceType !== null) {
        $query->where('service_type_id', $this->serviceType);
    }

    $orders = $query->get();

    // Si $isGrouped es true, agrupar los datos
    if ($this->isGrouped) {
        $groupedOrders = $orders->groupBy(['user_id', 'service_type_id'])
                                ->map(function ($userGroups) {
                                    return $userGroups->map(function ($serviceTypeGroups) {
                                        return [
                                            'user_id' => $serviceTypeGroups->first()->user_id,
                                            'service_type_id' => $serviceTypeGroups->first()->service_type_id,
                                            'total_products' => $serviceTypeGroups->sum('total_products'),
                                            'personal' => $serviceTypeGroups->first()->personal,
                                            'service_type' => $serviceTypeGroups->first()->service_type,
                                            'createdby' => $serviceTypeGroups->first()->createdby,
                                            'created_at' => $serviceTypeGroups->first()->created_at,
                                        ];
                                    });
                                })
                                ->flatten(1); // Aplanar el array para que sea una colección simple

        return $groupedOrders;
    }

    return $orders;
}
}

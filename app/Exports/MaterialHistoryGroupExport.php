<?php

namespace App\Exports;

use App\Models\MaterialHistory;
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

class MaterialHistoryGroupExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithDrawings, WithCustomStartCell, WithEvents
{
    protected $dateInput;
    protected $dateOutput;
    protected $products;

    protected $vendor_id;

    // Recibe los datos en el constructor
    public function __construct($dateInput, $dateOutput, $vendor_id)
    {
        $this->dateInput = $dateInput;
        $this->dateOutput = $dateOutput;
        $this->vendor_id = $vendor_id;
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
                $dateInput =  $this->dateInput ? Carbon::parse($this->dateInput)->format('d/m/Y') : '';
                $dateOutput = $this->dateOutput ?  Carbon::parse($this->dateOutput)->format('d/m/Y') : '';

                $titulo = "Reporte de Registros de Materia Prima, agrupados por proveedor [ENTRADAS], - $this->vendor_id de: {$dateInput} a {$dateOutput}";
                $event->sheet->setCellValue('A5', $titulo);                

                // Aquí puedes agregar más personalizaciones a la hoja después de generar los datos
            },
        ];
    }
    public function headings(): array
    {
       $headings = [
            __('Code'),
            __('Material'),
            __('Description'),
            __('Vendor'),
            __('Total'),
            __('No. of records'),
        ];

        return $headings;

    }

    /**
     * Mapear los registros para la exportación
     *
     * @param $group
     * @return array
     */
    public function map($group): array
    {
        // Aquí, $group es una colección de MaterialHistory para un vendor_id específico
        $vendor = $group->first()->material->vendor;
        $material = $group->first()->material;

        $totalQuantity = $group->sum('stock');  // Puedes cambiar 'quantity' por el campo que deseas sumar

        // Mapeamos los datos para cada grupo
        return [
            $material->part_number,               // Nombre
            $material->full_name_clear,               // Nombre
            $material->description,
            $vendor->name,                 // vendor_id
            $totalQuantity,              // Total de cantidad, por ejemplo
            count($group),               // Número de registros para este vendor
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $orders = MaterialHistory::whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59'])
                             ->whereHas('material', function ($query) {
                                 // Filtrar por vendor_id relacionado con material
                                 $query->where('vendor_id', $this->vendor_id);
                             })
                            ->where('stock', '>', 0)
                            ->with('audi', 'material')
                            ->get();

        return $orders->groupBy(function ($item) {
            return $item->material_id;  // Agrupamos por vendor_id de la relación material
        });
    }
}

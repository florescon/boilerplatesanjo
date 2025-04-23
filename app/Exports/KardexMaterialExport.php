<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class KardexMaterialExport implements FromArray, WithStyles, WithEvents, ShouldAutoSize
{
    protected $material;
    protected $startDate;
    protected $endDate;

    public function __construct($material, $startDate = null, $endDate = null)
    {
        $this->material = $material;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo de los encabezados de la tabla
            'A17:H17' => [ // Asumiendo que las filas de información son 8
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E0E0']
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
            // Estilo para el bloque de información del material
            'A1:B17' => [
                'font' => ['bold' => true],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Bordes para todo el contenido (desde encabezado en A9 hasta la última fila)
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A17:H{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                $sheet->setAutoFilter("A17:H17");
            },
        ];
    }


    public function array(): array
    {
        // Parte superior: info del material
        $infoRows = [
            ['Kardex'],
            ['Generado:', now()->format('d-m-Y h:i')],
            ['Rango', Carbon::parse($this->startDate)->format('d-m-Y').' - '.Carbon::parse($this->endDate)->format('d-m-Y')],
            ['Existencia', $this->material->stock.' '.optional($this->material->unit)->abbreviation],
            ['Precio Actual','$ '. $this->material->price],
            ['Precio Promedio','$ (pending)' ],
            ['Costo Total','$ '. $this->material->price * $this->material->stock],
            ['Nombre', $this->material->name],
            ['Código', $this->material->part_number],
            ['Color', optional($this->material->color)->name],
            ['Saldo Incial', '(pending)'],
            ['Saldo Final', '(pending)'],
            ['Descripción', $this->material->description],
            ['Proveedor', optional($this->material->vendor)->name],
            ['Entradas', $this->material->getTotalInput($this->startDate, $this->endDate)],
            ['Salidas', $this->material->getTotalOutput($this->startDate, $this->endDate)],
            [], // Fila vacía antes de los encabezados
        ];

        // Encabezados
        $headers = [[
            'Fecha',
            'Detalle',
            'Usuario',
            'Movimiento',
            'Costo',
            'Entrada',
            'Salida',
            'Balance',
        ]];

        // Datos del kardex
        $kardex = $this->material
            ->kardexRecords($this->startDate, $this->endDate)
            ->filter(function ($day) {
                    $date = Carbon::parse($day['date']);
                    $endDate = Carbon::parse($this->endDate);
                    return $date->between($this->startDate, $endDate);
            })
            ->flatMap(function ($grouped) {
                return $grouped['items']->map(function ($item) use ($grouped) {
                    return [
                        $grouped['date'],
                        $item['details'],
                        $item['user'],
                        $item['instanceof'] == true ? 'Manual' : 'Consumo',
                        $item['cost'],
                        $item['input'],
                        $item['output'],
                        $item['balance'],
                    ];
                });
            })
            ->values()
            ->all(); // Convertimos a array

        return array_merge($infoRows, $headers, $kardex);
    }

    public function title(): string
    {
        return 'Kardex Material';
    }

}

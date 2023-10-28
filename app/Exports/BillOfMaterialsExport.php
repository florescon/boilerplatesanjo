<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class BillOfMaterialsExport implements FromArray, WithMapping, WithHeadings, WithStyles
{
    private $productsIDs = [];

    public function __construct($productsIDs = False){
        $this->productsIDs = $productsIDs;
    }

    public function styles(Worksheet $sheet)
    {
        return [
           // Style the first row as bold text.
           1    => ['font' => ['bold' => true, 'alignment' => 'center']],
        ];
        // $sheet->getStyle('1')->getFont()->setBold(true);
    }

    public function headings(): array
    {
        return [
            __('Code'),
            __('Stock'),
            __('Material'),
            __('Exploded'),
            __('Require'),
            __('Unit'),
            __('Family'),
            __('Vendor'),
        ];
    }


    /**
    * @var Material $material
    */
    public function map($material): array
    {
        return [
            $material['part_number'] ?? '',
            $material['stock'] ?? '',
            $material['material_name'] ?? '',
            $material['quantity'] ?? '--',
            $material['stock'] < $material['quantity'] ? abs($material['stock'] - $material['quantity']) : '',
            $material['unit_measurement'] ?? '',
            $material['family'] ?? '--',
            $material['vendor'] ?? '',
        ];
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->productsIDs;
    }
}

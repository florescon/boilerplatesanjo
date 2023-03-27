<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class BillOfMaterialsExport implements FromArray, WithMapping, WithHeadings
{
    private $productsIDs = [];

    public function __construct($productsIDs = False){
        $this->productsIDs = $productsIDs;
    }

    public function headings(): array
    {
        return [
            __('Part number'),
            __('Material'),
            __('Exploded'),
            __('Stock'),
            __('Require'),
            __('Unit of measurement'),
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
            $material['material_name'] ?? '',
            $material['quantity'] ?? '--',
            $material['stock'] ?? '',
            $material['stock'] < $material['quantity'] ? abs($material['stock'] - $material['quantity']) : '',
            $material['unit_measurement'] ?? '',
            $material['family'] ?? '',
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

<?php

namespace App\Exports;

use App\Models\Departament;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DepartamentsExport implements FromCollection, WithMapping, WithHeadings, WithStyles
{
    private $departamentIDs = [];

    public function __construct($departamentIDs = False){

        $this->departamentIDs = $departamentIDs;
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
            __('Name'),
            __('Email'),
            __('Comment'),
            __('Updated at'),
        ];
    }

    /**
    * @var Invoice $departament
    */
    public function map($departament): array
    {
        return [
            $departament->name,
            $departament->email,
            $departament->comment,
            $departament->updated_at,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Departament::find($this->departamentIDs);
    }
}
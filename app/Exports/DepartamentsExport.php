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
use Maatwebsite\Excel\Concerns\WithTitle;

class DepartamentsExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithTitle
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
            __('Phone'),
            __('Address'),
            __('RFC'),
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
            $departament->phone,
            $departament->address,
            $departament->rfc,
            $departament->updated_at,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('Departaments').' '.now()->format('g:i a l jS F Y');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Departament::find($this->departamentIDs)->sortByDesc('name');
    }
}

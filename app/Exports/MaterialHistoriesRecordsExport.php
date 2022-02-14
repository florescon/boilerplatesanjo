<?php

namespace App\Exports;

use App\Models\MaterialHistory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class MaterialHistoriesRecordsExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithTitle
{
    private $materialIDs = [];

    public function __construct($materialIDs = False){
        $this->materialIDs = $materialIDs;
    }

    public function styles(Worksheet $sheet)
    {
        return [
           // Style the first row as bold text.
           1    => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        return [
            __('Feedstock'),
            __('Old stock'),
            __('Added stock'),
            __('Total stock'),
            __('Old price'),
            __('Price'),
            __('Created at'),
        ];
    }

    /**
    * @var Invoice $material_history
    */
    public function map($material_history): array
    {
        return [
            optional($material_history->material)->full_name_clear,
            $material_history->old_stock,
            $material_history->stock,
            $material_history->stock + $material_history->old_stock,
            '$'.$material_history->price,
            '$'.$material_history->price,
            $material_history->created_at,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('Feedstock history records').' '.now()->format('g:i a l jS F Y');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MaterialHistory::with('material', 'audi')->find($this->materialIDs)->sortByDesc('created_at');
    }}

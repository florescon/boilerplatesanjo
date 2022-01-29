<?php

namespace App\Exports;

use App\Models\Color;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ColorsExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithTitle
{
    private $colorIDs = [];

    public function __construct($colorIDs = False){
        $this->colorIDs = $colorIDs;
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
            __('Name'),
            __('Short name'),
            __('Color'),
            __('Created at'),
        ];
    }

    /**
    * @var Invoice $color
    */
    public function map($color): array
    {
        return [
            $color->name,
            $color->short_name,
            $color->color,
            $color->created_at,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('Colors').' '.now()->format('g:i a l jS F Y');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Color::find($this->colorIDs)->sortByDesc('name');
    }
}

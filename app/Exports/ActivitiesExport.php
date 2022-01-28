<?php

namespace App\Exports;

use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActivitiesExport implements FromCollection, WithMapping, WithHeadings, WithStyles
{
    private $activityIDs = [];

    public function __construct($activityIDs = False){

        $this->activityIDs = $activityIDs;
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
            __('Activity name'),
            __('Description'),
            __('Properties'),
            __('Created at'),
        ];
    }

    /**
    * @var Invoice $activity
    */
    public function map($activity): array
    {
        return [
            $activity->log_name,
            $activity->description,
            $activity->properties,
            $activity->created_at,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Activity::find($this->activityIDs)->sortByDesc('created_at');
    }
}

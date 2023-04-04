<?php

namespace App\Exports;

use App\Models\Finance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancesExport implements FromCollection, WithMapping, WithHeadings
{
    private $financesIDs = [];

    public function __construct($financesIDs = False){
        $this->financesIDs = $financesIDs;
    }

    public function headings(): array
    {
        return [
            __('Amount'),
            __('Name'),
            __('Type'),
            __('Date entered'),
            __('Created at'),
            __('Details'),
        ];
    }

    /**
    * @var Invoice $finance
    */
    public function map($finance): array
    {
        return [
            $finance->amount ? $finance->finance_sign.'$'.$finance->amount :  '',
            $finance->name ?? '',
            $finance->type ? $finance->formatted_type : '',
            $finance->date_entered ?? '',
            $finance->created_at ?? '',
            $finance->details_clear ?? '',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Finance::find($this->financesIDs)->sortBy('created_at');
    }
}

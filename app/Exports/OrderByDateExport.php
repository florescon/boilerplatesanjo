<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class OrderByDateExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithDrawings, WithCustomStartCell
{
    protected $dateInput;
    protected $dateOutput;
    protected $flowchart;

    // Recibe las fechas en el constructor
    public function __construct($dateInput, $dateOutput, $flowchart)
    {
        $this->dateInput = $dateInput;
        $this->dateOutput = $dateOutput;
        $this->flowchart = $flowchart;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('5')->getFont()->setBold(true);
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/img/logo2.png'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function headings(): array
    {
        return [
            __('Folio'),
            __('Total'),
            __('Customer'),
            __('Quotation'),
            __('Request n.º'),
            __('Purchase Order'),
            __('Created'),
            __('Comment'),
            __('Info customer'),
            __('Observations'),
        ];
    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        return [
            $product->folio,
            $product->total_products_by_all,
            $product->user_name_clear,
            $product->quotation,
            $product->request,
            $product->purchase,
            $product->date_for_humans,
            $product->comment,
            $product->info_customer,
            $product->observation,
        ];
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::with('user', 'departament')
                ->whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59'])
                ->when($this->flowchart, function ($query) {
                    return $query->where('flowchart', true);
                }, function ($query) {
                    return $query->where('flowchart', false);
                })
                ->whereType(1)
                ->orderBy('created_at')->get();
    }
}

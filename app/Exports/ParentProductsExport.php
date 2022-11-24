<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;


class ParentProductsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    private $productsIDs = [];

    public function __construct($productsIDs = False){
        $this->productsIDs = $productsIDs;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class    => function(BeforeSheet $event) {

                // Set cell A1 with Your Title
                $event->sheet->setCellValue('A1', 'Reporte de productos');

                // Set cells A2:B2 with current date
                $event->sheet->setCellValue('A2', __('Report Date:'));
                $event->sheet->setCellValue('B2', now());

                $cellRange = 'A1:H1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->getColor()
                            ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FF17a2b8');
                $event->sheet->setAutoFilter($cellRange);

                $cellRange = 'A3:H3'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

            },

            AfterSheet::class    => function(AfterSheet $event) {
                $cellRangeHead = 'A4:H4'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRangeHead)->getFont()->setSize(14);
                $event->sheet->setAutoFilter($cellRangeHead);
            },

        ];
    }

    public function headings(): array
    {
        return [
            __('Code'),
            __('Name'),
            __('Cost'),
            __('Retail price'),
            __('Average wholesale price'),
            __('Wholesale price'),
            __('Special price'),
            __('Brand'),
        ];
    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        return [
            $product->code,
            $product->name,
            $product->cost,
            $product->price,
            $product->average_wholesale_price,
            $product->wholesale_price,
            $product->special_price,
            optional($product->brand)->name,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with('color', 'size')->find($this->productsIDs)->sortBy('name');
    }
}

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
    protected $getType;
    protected $fromStore;

    // Recibe los datos en el constructor
    public function __construct($dateInput, $dateOutput, ?bool $flowchart = false, ?int $getType = 1, ?bool $fromStore = false)
    {
        $this->dateInput = $dateInput;
        $this->dateOutput = $dateOutput;
        $this->flowchart = $flowchart;
        $this->getType = $getType;
        $this->fromStore = $fromStore;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('5')->getFont()->setBold(true);
        !$this->fromStore ? $sheet->setAutoFilter('A5:K5') : $sheet->setAutoFilter('A5:H5');
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
       $headings = [
            __('Created'),
            __('Order'),
            __('Customer'),
            __('Comment'),
            __('Products'),
            __('Services'),
            __('Quotation'),
            __('Request n.º'),
            __('Purchase Order'),
            __('Info customer'),
            __('Observations'),
        ];

        if ($this->fromStore) {
            unset($headings[7], $headings[8]);
        }

        return $headings;

    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        $data = [
            $product->date_for_humans,
            $product->folio,
            $product->user_name_clear,
            $product->comment,
            $product->total_products_by_all_products,
            $product->total_products_by_all_services,
            $product->quotation,
            $product->request,
            $product->purchase,
            $product->info_customer,
            $product->observation,
        ];

        if ($this->fromStore) {
            unset($data[7], $data[8]);
        }

        return $data;

    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::with('user', 'departament', 'products.product')
                ->whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59'])
                ->whereType($this->getType)
                ->when($this->fromStore, function($query){
                    return $query->where('from_store', true);
                }, function($query){
                    return $query
                        ->when($this->flowchart, function ($querysec) {
                            return $querysec->where('flowchart', true);
                        }, function ($querysec) {
                            return $querysec->where('flowchart', false);
                        });
                })
                ->orderBy('created_at')->get();
    }
}

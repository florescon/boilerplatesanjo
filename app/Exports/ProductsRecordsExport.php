<?php

namespace App\Exports;

use App\Models\ProductOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsRecordsExport implements FromCollection, WithMapping, WithHeadings, WithStyles
{
    private $serviceIDs = [];

    public function __construct($serviceIDs = False){
        $this->serviceIDs = $serviceIDs;
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
            __('Quantity'),
            __('Price'),
            __('Order/Sale'),
            __('Type'),
            __('Created at'),
        ];
    }

    /**
    * @var Invoice $product_order
    */
    public function map($product_order): array
    {
        return [
            optional($product_order->product)->full_name_clear,
            $product_order->quantity,
            $product_order->price,
            $product_order->order_id,
            $product_order->type_order,
            $product_order->created_at,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProductOrder::with('product.parent', 'product.color', 'product.size')->find($this->serviceIDs);
    }
}

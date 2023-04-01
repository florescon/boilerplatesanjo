<?php

namespace App\Exports;

use App\Models\MaterialOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class MaterialsRecordsExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithTitle
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
            __('Code'),
            __('Feedstock'),
            __('Product'),
            __('Consumption'),
            __('Total consumption'),
            __('Unit price'),
            __('Price'),
            __('Order'),
            __('Created at'),
        ];
    }

    /**
    * @var Invoice $material_order
    */
    public function map($material_order): array
    {
        return [
            optional($material_order->material)->part_number,
            optional($material_order->material)->full_name_clear,
            optional($material_order->product_order->product)->full_name_clear,
            $material_order->unit_quantity,
            $material_order->quantity,
            '$'.$material_order->price,
            '$'.rtrim(rtrim(sprintf('%.8F', $material_order->total_by_material), '0'), "."),
            $material_order->order_id,
            $material_order->created_at,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('Feedstock records').' '.now()->format('g:i a l jS F Y');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MaterialOrder::with('material.color', 'material.size', 'material.unit', 'product_order.product.color', 'product_order.product.size')->find($this->materialIDs)->sortByDesc('created_at');
    }
}

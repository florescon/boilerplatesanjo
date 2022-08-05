<?php

namespace App\Exports;

use App\Models\ProductHistory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductKardexExport implements FromCollection, WithMapping, WithHeadings
{
    private $productsIDs = [];

    public function __construct($productsIDs = False){
        $this->productsIDs = $productsIDs;
    }

    public function headings(): array
    {
        return [
            __('Name'),
            __('Input'),
            __('Output'),
            __('Old stock'),
            __('Balance'),
            __('Date'),
        ];
    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        return [
            optional($product->product)->name.', '.optional($product->subproduct->color)->name.' '.optional($product->subproduct->size)->name,
            !$product->isOutput() ? $product->stock : null,
            $product->isOutput() ? $product->stock : null,
            $product->old_stock ?? '--',
            $product->balance,
            $product->created_at,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProductHistory::with('product', 'subproduct.color', 'subproduct.size')->find($this->productsIDs)->sortByDesc('created_at');
    }
}

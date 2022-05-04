<?php

namespace App\Exports;

use App\Models\ProductInventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductInventoriesExport implements FromCollection, WithMapping, WithHeadings
{
    private $productsIDs = [];

    public function __construct($productsIDs = False){
        $this->productsIDs = $productsIDs;
    }

    public function headings(): array
    {
        return [
            __('Code'),
            __('Product'),
            __('Captured'),
            __('Stock'),
            __('Difference'),
            __('Detail'),
        ];
    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        return [
            $product->product->code ? $product->product->code : optional($product->product->parent)->code,
            optional($product->product->parent)->name.', '.optional($product->product->color)->name.' '.optional($product->product->size)->name,
            $product->capture,
            $product->stock,
            $product->difference(),
            $product->description_difference_formatted,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProductInventory::with('product.color', 'product.parent', 'product.size')->find($this->productsIDs)->sortBy('product.parent.name');
    }
}

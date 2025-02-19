<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductMainExport implements FromCollection, WithMapping, WithHeadings
{
    private $productsIDs = [];
    private $from_store;

    public function __construct($productsIDs = False, $from_store = false){
        $this->productsIDs = $productsIDs;
        $this->from_store = $from_store;
    }

    public function headings(): array
    {
        return [
            __('Code'),
            __('Stock'),
            __('Name'),
            __('Color'),
            __('Size_'),
            __('Price'),
            __('Vendor'),
            __('Brand'),
        ];
    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        return [
            $product->code ? $product->code : optional($product->parent)->code,
            $this->from_store ? ($product->stock_store ?? 0) : ($product->stock ?? 0),
            optional($product->parent)->name,
            optional($product->color)->name,
            optional($product->size)->name,
            optional($product->parent)->cost ?? 0,
            $product->parent_id ? optional($product->parent->vendor)->short_name : '',
            $product->parent_id ? optional($product->parent->brand)->name : '',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with('parent', 'color', 'size')->find($this->productsIDs)->sortBy('code');
    }
}

<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithMapping, WithHeadings
{
    private $productsIDs = [];

    public function __construct($productsIDs = False){
        $this->productsIDs = $productsIDs;
    }

    public function headings(): array
    {
        return [
            __('Name'),
            __('Code'),
            __('Stock'),
            __('Revision stock'),
            __('Store stock'),
        ];
    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        return [
            optional($product->parent)->name.', '.optional($product->color)->name.' '.optional($product->size)->name,
            $product->code ? $product->code : optional($product->parent)->code,
            $product->stock,
            $product->stock_revision,
            $product->stock_store,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with('parent', 'color', 'size')->find($this->productsIDs)->sortByDesc('created_at');
    }
}
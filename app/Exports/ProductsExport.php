<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithMapping, WithHeadings
{
    private $productsIDs = [];

    public function __construct($productsIDs = False){
        $this->productsIDs = $productsIDs;
    }

    public function headings(): array
    {
        return [
            __('Code'),
            __('Stock'),
            __('Name'),
            __('Store stock'),
            __('Cost'),
            __('Retail price'),
            __('Average wholesale price'),
            __('Wholesale price'),
            __('Special price'),
        ];
    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        return [
            $product->code ? $product->code : optional($product->parent)->code,
            $product->stock,
            optional($product->parent)->name.', '.optional($product->color)->name.' '.optional($product->size)->name,
            $product->stock_store,
            optional($product->parent)->cost,
            $product->hasPriceSubproduct() ?: optional($product->parent)->price,
            $product->hasAverageWholesalePriceSubproduct() ?: optional($product->parent)->average_wholesale_price,
            $product->hasWholesalePriceSubproduct() ?: optional($product->parent)->wholesale_price,
            $product->hasSpecialPriceSubproduct() ?: optional($product->parent)->special_price,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with('parent', 'color', 'size')->find($this->productsIDs)->sortBy('parent.name');
    }
}
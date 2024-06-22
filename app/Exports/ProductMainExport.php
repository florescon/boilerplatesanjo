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

    public function __construct($productsIDs = False, $from_store){
        $this->productsIDs = $productsIDs;
        $this->from_store = $from_store;
    }

    public function headings(): array
    {
        return [
            __('Code'),
            __('Name'),
            __('Price'),
            __('Stock'),
        ];
    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        return [
            $product->code ? $product->code : optional($product->parent)->code,
            optional($product->parent)->name.', '.optional($product->color)->name.' '.optional($product->size)->name,
            optional($product->parent)->cost ?? 0,
            $this->from_store ? ($product->stock_store ?? 0) : ($product->stock ?? 0),
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

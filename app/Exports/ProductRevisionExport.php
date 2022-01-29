<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductRevisionExport implements FromCollection, WithMapping, WithHeadings
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
            __('Revision stock'),
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
            $product->stock_revision ?? 0,
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

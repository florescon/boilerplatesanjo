<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParentProductsExport implements FromCollection, WithMapping, WithHeadings
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
            __('Retail price'),
            __('Average wholesale price'),
            __('Wholesale price'),
        ];
    }

    /**
    * @var Invoice $product
    */
    public function map($product): array
    {
        return [
            $product->name,
            $product->code,
            $product->price,
            $product->average_wholesale_price,
            $product->wholesale_price,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with('color', 'size')->find($this->productsIDs)->sortBy('name');
    }
}

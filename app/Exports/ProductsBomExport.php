<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class ProductsBomExport implements FromCollection, WithMapping, WithHeadings
{
    private $productsIDs = [];

    public function __construct($productsIDs = False){
        $this->productsIDs = $productsIDs;
    }

    public function headings(): array
    {
        return [
            __('Quantity'),
            __('Code'),
            __('Product'),
            __('Color'),
            __('Request'),
            __('Customer'),
        ];
    }


    public function map($product): array
    {
        return [
            $product['productQuantity'] ?? '',
            $product['productParentCode'] ?: '--',
            $product['productName'] ?? '',
            $product['productColorName'] ?: '--',
            $product['productOrder'] ? '#'.$product['productOrder'] : '',
            $product['customer'],
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->productsIDs->sortBy([
            ['productColorName'],
            ['productName', 'asc'], 
        ]);
    }
}

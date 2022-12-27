<?php

namespace App\Exports;

use App\Domains\Auth\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientsExport implements FromCollection, WithMapping, WithHeadings
{
    private $customersIDs = [];

    public function __construct($customersIDs = False){
        $this->customersIDs = $customersIDs;
    }

    public function headings(): array
    {
        return [
            __('Name'),
            __('Email'),
            __('Type price'),
        ];
    }

    /**
    * @var Invoice $customer
    */
    public function map($customer): array
    {
        return [
            $customer->name ?? '',
            $customer->email ?? '',
            optional($customer->customer)->type_price ?? __('Retail price'),
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with('customer')->find($this->customersIDs)->sortBy('name');
    }
}

<?php

namespace App\Exports;

use App\Models\Finance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancesExport implements FromCollection, WithMapping, WithHeadings
{
    private $financesIDs = [];

    protected $dateInput;
    protected $dateOutput;

    protected $onlyIncomes;
    protected $onlyExpenses;

    public function __construct($financesIDs = False, ?string $dateInput = '', ?string $dateOutput = '', ?bool $onlyIncomes, ?bool $onlyExpenses){

        $this->financesIDs = $financesIDs;
        $this->dateInput = $dateInput;
        $this->dateOutput = $dateOutput;
        $this->onlyIncomes = $onlyIncomes;
        $this->onlyExpenses = $onlyExpenses;
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('Customer'),
            __('Order'),
            __('Cash'),
            __('Transfer'),
            __('Other'),
            __('Advance'),
            __('Total Request'),
            __('Name'),
            __('Comment'),
            __('Type'),
            __('Payment method'),
            __('Invoice'),
            __('Captured'),
            __('Seller',)
        ];
    }

    /**
    * @var Invoice $finance
    */
    public function map($finance): array
    {
        return [
            $finance->date_entered->formatLocalized('%d-%m-%Y') ?? '',
            $finance->customer_order,
            $finance->order_id ?  optional($finance->order)->folio_or_id_clear : '',
            $finance->isCashPaymentMethod() ? $finance->finance_sign.'$'.$finance->amount :  '',
            $finance->isTransferPaymentMethod() ? $finance->finance_sign.'$'.$finance->amount :  '',
            !$finance->isTransferPaymentMethod() && !$finance->isCashPaymentMethod() ? $finance->finance_sign.'$'.$finance->amount :  '',
            $finance->order_id ? $finance->quantity_advance : 'N/A',
            $finance->order_id ? '$'.optional($finance->order)->total_by_all : 'N/A',
            $finance->name ?? '',
            $finance->comment,
            $finance->type ? $finance->formatted_type : '',
            $finance->payment_method,
            $finance->is_invoice,
            $finance->audi_id ? optional($finance->audi)->name : '--',
            $finance->order->seller_id ? optional($finance->order->seller)->name : '--',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = '';

        if($this->financesIDs){
            $query = Finance::find($this->financesIDs)->sortBy('created_at');
        }
        else { 

            $query = Finance::whereBetween('created_at', [$this->dateInput . ' 00:00:00', $this->dateOutput . ' 23:59:59'])
                ->when($this->onlyIncomes && !$this->onlyExpenses, function ($q) {
                    return $q->where('type', 'income');
                })
                ->when($this->onlyExpenses && !$this->onlyIncomes, function ($q) {
                    return $q->where('type', 'expense');
                })
                // Si ambos son verdaderos o ambos son falsos, no filtramos por 'type'
                ->with('order.user', 'audi', 'payment')
                ->get();

        }

        return $query;
    }
}

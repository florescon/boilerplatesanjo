<?php

namespace App\Http\Livewire\Backend\Report;

use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;
use Excel;
use App\Exports\OrderProductsByDateExport;
use App\Exports\OrderProductsReportExport;
use App\Exports\OrderProductsReportGroupedExport;
use App\Exports\ServiceOrderExport;
use App\Exports\MaterialHistoryGroupExport;
use App\Exports\FinancesExport;
use Carbon\Carbon;
use App\Exports\OrderByDateExport;

class ReportStoreTable extends Component
{
    public $dateInput = '';
    public $dateOutput = '';

    public ?bool $details = false;

    protected $queryString = [
        'details' => ['except' => false],
    ];

    public $personal;
    public $service_type_id;
    public $vendor_id;

    protected $listeners = ['selectedCompanyItem', 'emitVendor', 'serviceTypeItem', 'triggerRefresh' => '$refresh'];

    public function selectedCompanyItem($personal)
    {
        $this->personal = $personal;
    }

    public function emitVendor($vendor)
    {
        $this->vendor_id = $vendor;
    }

    public function serviceTypeItem($serviceType)
    {
        $this->service_type_id = $serviceType;
    }

    public function clearPersonal()
    {
        $this->personal = null;
        $this->emit('clear-personal');
    }

    public function exportOrderProductsMaatwebsite($extension, ?bool $isProduct = false, ?bool $isService = false)
    {   
        $extension = 'xlsx';

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new OrderProductsReportExport($this->dateInput, $this->dateOutput, $isProduct, $isService, true), 'product-list-store-'.Carbon::now().'.'.$extension);

    }

    public function exportOrderProductsGroupedMaatwebsite($extension, ?bool $isProduct = false, ?bool $isService = false)
    {   
        $extension = 'xlsx';

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new OrderProductsReportGroupedExport($this->dateInput, $this->dateOutput, $isProduct, $isService, true), 'product-grouped-list-store-'.Carbon::now().'.'.$extension);

    }

    public function exportMaatwebsite($extension, ?bool $isProduct = false, ?bool $isService = false, ?bool $isStore = false, ?bool $isGrouped = false)
    {   
        $extension = 'xlsx';

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new OrderProductsByDateExport($this->dateInput, $this->dateOutput, $isProduct, $isService, $isStore, $isGrouped), 'product-list-store-'.Carbon::now().'.'.$extension);

    }

    public function exportServiceOrdersMaatwebsite($extension, ?bool $isGrouped = false)
    {   
        $extension = 'xlsx';

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new ServiceOrderExport($this->dateInput, $this->dateOutput, $this->service_type_id, $isGrouped), 'product-list-store-'.Carbon::now().'.'.$extension);

    }

    public function exportFinancesMaatwebsite($extension, ?bool $onlyIncomes = false, ?bool $onlyExpenses = false)
    {   
        $extension = 'xlsx';

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new FinancesExport(false, $this->dateInput, $this->dateOutput, $onlyIncomes, $onlyExpenses), 'incomes_and_expenses-'.Carbon::now().'.'.$extension);

    }

    public function exportMaterialHistoryMaatwebsite($extension)
    {   
        $extension = 'xlsx';

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new MaterialHistoryGroupExport($this->dateInput, $this->dateOutput, $this->vendor_id), 'product-list-store-'.Carbon::now().'.'.$extension);

    }

    public function printExportOrdersForDate()
    {   
        $extension = 'xlsx';

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new OrderByDateExport($this->dateInput, $this->dateOutput, true, 5, true), 'order-list-store-'.Carbon::now().'.'.$extension);
    }

    public function render()
    {
        return view('backend.report.livewire.report-store-table');
    }
}

<?php

namespace App\Http\Livewire\Backend\Report;

use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;
use Excel;
use App\Exports\OrderProductsByDateExport;
use App\Exports\OrderProductsReportExport;
use App\Exports\OrderProductsReportGroupedExport;
use Carbon\Carbon;

class ReportTable extends Component
{
    public $dateInput = '';
    public $dateOutput = '';

    public ?bool $details = false;

    protected $queryString = [
        'details' => ['except' => false],
    ];

    public $personal;

    protected $listeners = ['selectedCompanyItem', 'triggerRefresh' => '$refresh'];

    public function selectedCompanyItem($personal)
    {
        $this->personal = $personal;
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
        return Excel::download(new OrderProductsReportExport($this->dateInput, $this->dateOutput, $isProduct, $isService), 'product-list-'.Carbon::now().'.'.$extension);

    }

    public function exportOrderProductsGroupedMaatwebsite($extension, ?bool $isProduct = false, ?bool $isService = false)
    {   
        $extension = 'xlsx';

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new OrderProductsReportGroupedExport($this->dateInput, $this->dateOutput, $isProduct, $isService), 'product-list-'.Carbon::now().'.'.$extension);

    }

    public function exportMaatwebsite($extension, ?bool $isProduct = false, ?bool $isService = false)
    {   
        $extension = 'xlsx';

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new OrderProductsByDateExport($this->dateInput, $this->dateOutput, $isProduct, $isService), 'product-list-'.Carbon::now().'.'.$extension);

    }

    public function render()
    {
        return view('backend.report.livewire.report-table');
    }
}

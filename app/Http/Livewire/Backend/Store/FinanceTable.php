<?php

namespace App\Http\Livewire\Backend\Store;

use App\Models\Finance;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\FinancesExport;
use Illuminate\Database\Eloquent\Builder;
use Excel;
use DB;

class FinanceTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'deleted' => ['except' => FALSE],
        'incomes' => ['except' => FALSE],
        'expenses' => ['except' => FALSE],
        'history' => ['except' => FALSE],
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => ''],
    ];

    public $perPage = '10';
    public $limitPerPage = '100';

    public $sortField = 'created_at';
    public $sortAsc = false;
    
    public $searchTerm = '';

    public ?int $paymentFilter = null;

    public $dateInput = '';
    public $dateOutput = '';

    public bool $currentMonth = false;
    public bool $currentWeek = false;
    public bool $today = false;
    public bool $history = false;

    public bool $invoice = false;

    public $records_sum;

    public $status;

    protected $listeners = ['filter' => 'filter', 'bill', 'delete', 'restore', 'forceRender' => 'render', 'refreshFinanceTable' => '$refresh'];

    public $name, $short_name, $color, $secondary_color, $created, $updated, $selected_id, $deleted;

    public bool $incomes = false;
    public bool $expenses = false;

    public function filter($type_finance)
    {
        $this->resetPage();

        if($type_finance === 'incomes'){
            $this->expenses = false;
            $this->incomes = ! $this->incomes;
        }

        if($type_finance === 'expenses'){
            $this->incomes = false;
            $this->expenses = ! $this->expenses;
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function export()
    {
        return response()->streamDownload(function () {
            echo $this->selectedRowsQuery->toCsv();
        }, 'color-list.csv');
    }

    private function getSelectedProducts()
    {
        return $this->selectedRowsQuery->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        // return $this->selectedRowsQuery->where('stock_store', '>', 0)->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }
    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new FinancesExport($this->getSelectedProducts()), 'product-list-'.Carbon::now().'.'.$extension);
    }

    public function getRowsQueryProperty()
    {
        $query = Finance::query()->with('user', 'payment', 'order', 'departament', 'cash')
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                    $query->whereBetween('created_at', [$this->dateInput.' 00:00:00', now()]) :
                    $query->whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
            })
            ->when($this->currentMonth, function ($query) {
                $this->applySum($query);
                $query->currentMonth();
            })
            ->when($this->currentWeek, function ($query) {
                $this->applySum($query);
                $query->currentWeek();
            })
            ->when($this->today, function ($query) {
                $this->applySum($query);
                $query->today();
            })
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            })
            ->when(!$this->history, function ($query) {
                $this->applySum($query);
                $query->where('cash_id', null);
            })
            ;

        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        if ($this->incomes === TRUE) {
            $this->applyPaymentFilter($query);
            $this->applySearchFilter($query);
            $this->applyInvoiceFilter($query);
            $this->applySum($query);
            return $query->onlyIncomes();
        }

        if ($this->expenses === TRUE) {
            $this->applyPaymentFilter($query);
            $this->applySearchFilter($query);
            $this->applyInvoiceFilter($query);
            $this->applySum($query);
            return $query->onlyExpenses();
        }

        $this->applyPaymentFilter($query);
        $this->applyInvoiceFilter($query);

        $this->applySearchFilter($query);

        return $query;
    }

    private function applySum($querySum)
    {   
        if($this->incomes){
            return $this->records_sum = $querySum->whereType('income')->sum('amount');
        }
        if($this->expenses){
            return $this->records_sum = $querySum->whereType('expense')->sum('amount');
        }
    }

    private function applySearchFilter($searchFinance)
    {
        if ($this->searchTerm) {
            return $searchFinance->where(function(Builder $query) {
                        $query->whereRaw("name LIKE \"%$this->searchTerm%\"")
                                ->orWhereRaw("comment LIKE \"%$this->searchTerm%\"")
                                ->orWhereRaw("ticket_text LIKE \"%$this->searchTerm%\"")
                                ->orWhereRaw("id LIKE \"%$this->searchTerm%\"")
                                ->orWhereRaw("amount LIKE \"%$this->searchTerm%\"");
                        });
        }

        return null;
    }

    public function filterPayment(int $paymentMethod)
    {
        if(!$this->paymentFilter)
            $this->paymentFilter = $paymentMethod;
        else
            $this->paymentFilter = null;
    }

    private function applyPaymentFilter($finances)
    {
        if ($this->paymentFilter) {
            $filter = $this->paymentFilter;
            $finances->whereHas('payment', function ($query) use ($filter) {
                $query->where('payment_method_id', $filter);
            });
        }

        return null;
    }

    private function applyInvoiceFilter($finances)
    {
        if ($this->invoice) {
            $finances->where('is_bill', true);
        }

        return null;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate(($this->perPage > $this->limitPerPage) ? $this->clear() : $this->perPage);
        });
    }

    public function clearFilterDate()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->clearRangeDate();
    }

    public function clearRangeDate()
    {
        $this->currentWeek = FALSE;
        $this->today = FALSE;
        $this->currentMonth = FALSE;
    }

    public function isCurrentMonth()
    {
        $this->resetPage();
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->currentWeek = FALSE;
        $this->today = FALSE;

        if($this->currentMonth){
            $this->currentMonth = false;
        }
        else{
            $this->currentMonth = TRUE;
        }
    }

    public function isCurrentWeek()
    {
        $this->resetPage();
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->currentMonth = FALSE;
        $this->today = FALSE;

        if($this->currentWeek){
            $this->currentWeek = false;
        }
        else{
            $this->currentWeek = TRUE;
        }
    }

    public function isToday()
    {
        $this->resetPage();
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->currentMonth = FALSE;
        $this->currentWeek = FALSE;

        if($this->today){
            $this->today = false;
        }
        else{
            $this->today = TRUE;
        }
    }

    public function isInvoice()
    {
        $this->resetPage();

        if($this->invoice){
            $this->invoice = FALSE;
        }
        else{
            $this->invoice = TRUE;
        }
    }

    public function isHistory()
    {
        $this->resetPage();
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->currentMonth = FALSE;
        $this->currentWeek = FALSE;

        if($this->history){
            $this->history = false;
        }
        else{
            $this->history = TRUE;
        }
    }

    public function clearAll()
    {
        $this->paymentFilter = null;
        $this->clearFilterDate();
        $this->searchTerm = '';
        $this->history = FALSE;
        $this->resetPage();
        $this->perPage = '10';
        $this->deleted = FALSE;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
        $this->invoice = FALSE;
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '10';
    }

    public function updatedDateInput()
    {
        $this->resetPage();
        $this->currentMonth = FALSE;
        $this->currentWeek = FALSE;
        $this->today = FALSE;
    }

    public function updatedDateOutput()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedDeleted()
    {
        $this->resetPage();
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function delete(int $id)
    {
        if($id){
            $finance = Finance::where('id', $id);
            $finance->delete();
        }
       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function bill(?int $id = null)
    {
        if($id){
            $finance = Finance::withTrashed()->find($id);
            
            $finance->update([
                'is_bill' => $finance->is_bill ? false : true,
            ]);

            sleep(1);
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Changed'), 
        ]);
    }

    public function render()
    {
        $date = Carbon::now()->startOfMonth();

        $querySum = $this->records_sum;
        // $finances = DB::table('finances')->get();

        // foreach($finances as $finance){
        //     DB::table('finances')->where('id', $finance->id)->update([
        //             'updated_at' => Carbon::parse($finance->updated_at)->subMonth()
        //         ]);
        // }

        return view('backend.store.livewire.finance-table', [
            'finances' => $this->rows,
            'date' => $date,
            'querySum' => $this->records_sum,
        ]);
    }
}

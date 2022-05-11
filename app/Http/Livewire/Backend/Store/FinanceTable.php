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
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => '']
    ];

    public $perPage = '10';

    public $sortField = 'created_at';
    public $sortAsc = false;
    
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

    public bool $currentMonth = false;
    public bool $currentWeek = false;
    public bool $today = false;

    public $status;

    protected $listeners = ['filter' => 'filter', 'delete', 'restore', 'refreshFinanceTable' => '$refresh'];

    public $name, $short_name, $color, $secondary_color, $created, $updated, $selected_id, $deleted;

    public bool $incomes = false;
    public bool $expenses = false;

    public function filter($type_finance)
    {
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

    public function getRowsQueryProperty()
    {
        $query = Finance::query()->with('user', 'payment', 'order', 'departament', 'cash')
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                    $query->whereBetween('created_at', [$this->dateInput.' 00:00:00', now()]) :
                    $query->whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
            })
            ->when($this->currentMonth, function ($query) {
                    $query->currentMonth();
            })
            ->when($this->currentWeek, function ($query) {
                    $query->currentWeek();
            })
            ->when($this->today, function ($query) {
                    $query->today();
            })
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            });

        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        if ($this->incomes === TRUE) {
            return $query->onlyIncomes();
        }
        if ($this->expenses === TRUE) {
            return $query->onlyExpenses();
        }

        $this->applySearchFilter($query);

        return $query;
    }

    private function applySearchFilter($searchFinance)
    {
        if ($this->searchTerm) {
            return $searchFinance->whereRaw("name LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("comment LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("ticket_text LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("amount LIKE \"%$this->searchTerm%\"");
        }

        return null;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
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
        $this->clearFilterDate();
        $this->currentWeek = FALSE;
        $this->today = FALSE;
        $this->currentMonth = TRUE;
    }

    public function isCurrentWeek()
    {
        $this->clearFilterDate();
        $this->currentMonth = FALSE;
        $this->today = FALSE;
        $this->currentWeek = TRUE;
    }

    public function isToday()
    {
        $this->clearFilterDate();
        $this->currentMonth = FALSE;
        $this->currentWeek = FALSE;
        $this->today = TRUE;
    }

    public function clearAll()
    {
        $this->clearFilterDate();
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '10';
        $this->deleted = FALSE;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '10';
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    // public function hydratesortField()
    // {
    //     $this->resetPage();
    // }

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

    public function render()
    {
        $date = Carbon::now()->startOfMonth();
        return view('backend.store.livewire.finance-table', [
            'finances' => $this->rows,
            'date' => $date,
        ]);
    }
}

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

    public $status;

    protected $listeners = ['filter' => 'filter', 'delete', 'restore', 'triggerRefresh' => '$refresh'];

    public $name, $short_name, $color, $secondary_color, $created, $updated, $selected_id, $deleted;

    public bool $incomes = false;
    public bool $expenses = false;

    protected $rules = [
        'name' => 'required|min:3',
        'short_name' => 'required|min:1|unique:colors',
        'color' => 'required|unique:colors',
        'secondary_color' => '',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

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
        $query = Finance::query()->with('user')
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                    $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', now()]) :
                    $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
            })
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            });


        $this->applySearchFilter($query);

        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        if ($this->incomes === TRUE) {
            return $query->onlyIncomes();
        }
        if ($this->expenses === TRUE) {
            return $query->onlyExpenses();
        }

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
    }

    public function clearAll()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->searchTerm = '';
        $this->page = 1;
        $this->perPage = '10';
        $this->deleted = FALSE;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }


    public function clear()
    {
        $this->searchTerm = '';
        $this->page = 1;
        $this->perPage = '10';
    }

    public function updatedSearchTerm()
    {
        $this->page = 1;
    }

    public function hydratesortField()
    {
        $this->page = 1;
    }

    public function updatedPerPage()
    {
        $this->page = 1;
    }

    public function updatedDeleted()
    {
        $this->page = 1;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function delete($id)
    {
        if($id){
            $color = Finance::where('id', $id);
            $color->delete();
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

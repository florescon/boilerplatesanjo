<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Out;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;

class OutHistoryTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'deleted' => ['except' => FALSE],
        'pending' => ['except' => FALSE],
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => '']
    ];

    public $perPage = '10';

    public $sortField = 'id';
    public $sortAsc = false;
    
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

    public bool $currentMonth = false;
    public bool $currentWeek = false;
    public bool $today = false;

    public bool $pending = false;

    public $status;

    protected $listeners = ['filter' => 'filter', 'delete', 'restore', 'triggerRefresh' => '$refresh'];

    public $updated, $selected_id, $deleted;

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
        $query = Out::query()->with('feedstocks')
            ->where('type', 'out_product')
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
            $this->applySearchDeletedFilter($query);

            return $query->onlyTrashed();
        }
        else{
            $this->applySearchFilter($query);
        }        

        return $query;
    }

    private function applySearchFilter($searchFinance)
    {
        if ($this->searchTerm) {
            return $searchFinance->whereHas('customer', function ($query) {
               $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
            })
            ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
        }

        return null;
    }

    private function applySearchDeletedFilter($searchFinance)
    {
        if ($this->searchTerm) {
            return $searchFinance->onlyTrashed()
                    ->whereRaw("id LIKE \"%$this->searchTerm%\"");
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

    public function updatedPerPage()
    {
        $this->resetPage();
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

    public function updatedDeleted()
    {
        $this->resetPage();
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function delete($id)
    {
        if($id)
            $out = Out::where('id', $id);
            $out->delete();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        $date = Carbon::now()->startOfMonth();
        return view('backend.product.livewire.out-history-table', [
            'tickets' => $this->rows,
            'date' => $date,
        ]);
    }
}

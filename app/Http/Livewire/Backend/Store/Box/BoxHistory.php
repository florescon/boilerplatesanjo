<?php

namespace App\Http\Livewire\Backend\Store\Box;

use Livewire\Component;
use App\Models\Cash;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;

class BoxHistory extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'deleted' => ['except' => FALSE],
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

    public $status;

    protected $listeners = ['filter' => 'filter', 'delete', 'restore', 'triggerRefresh' => '$refresh'];

    public $name, $short_name, $color, $secondary_color, $created, $updated, $selected_id, $deleted;


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
        $query = Cash::query()
            ->where('checked', true)
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
            return $searchFinance->whereRaw("title LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("comment LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("initial LIKE \"%$this->searchTerm%\"");
        }

        return null;
    }

    private function applySearchDeletedFilter($searchFinance)
    {
        if ($this->searchTerm) {
            return $searchFinance->onlyTrashed()
                    ->whereRaw("title LIKE \"%$this->searchTerm%\"")
                    ->orWhereRaw("comment LIKE \"%$this->searchTerm%\"");
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
            $color = Cash::where('id', $id);
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
        return view('backend.store.livewire.box-history-table', [
            'cashes' => $this->rows,
            'date' => $date,
            'latest_box_history' => Cash::query()->latest('id')->where('checked', true)->first() ?? null,
        ]);
    }
}

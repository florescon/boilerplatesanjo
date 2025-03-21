<?php

namespace App\Http\Livewire\Backend\Station;

use Livewire\Component;
use App\Models\Station;
use App\Models\Status;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class StationTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    public ?string $theName = '';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'selectStatus',
        'deleted' => ['except' => FALSE],
        'pending' => ['except' => FALSE],
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => ''],
        'history' => ['except' => FALSE],
    ];

    public $perPage = '10';

    public $sortField = 'id';
    public $sortAsc = false;
    
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

    public int $typeStation;

    public bool $currentMonth = false;
    public bool $currentWeek = false;
    public bool $today = false;

    public bool $history = false;

    public bool $pending = false;

    public $status;
    public ?string $selectStatus = null;
    public ?string $statusName = '';


    protected $listeners = ['filter' => 'filter', 'delete', 'restore', 'triggerRefresh' => '$refresh'];

    public $updated, $selected_id, $deleted;


    public function mount()
    {
        // Inicializar selectStatus si es necesario
        $this->selectStatus = $this->selectStatus ?? null;
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
        $query = Station::query()
            ->with('product_station', 'order', 'status', 'personal')
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
            ->when($this->selectStatus, function ($query) {
                    $query->where('status_id', $this->selectStatus);  
            })
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            })
            ->when(!$this->history, function ($query) {
                $query->where('active', true);
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

    private function applySearchFilter($searchStation)
    {
        if ($this->searchTerm) {
            return $searchStation
            ->where(function (Builder $query) {
                $query->whereHas('order', function ($quer) {
                   $quer->whereRaw("comment LIKE \"%$this->searchTerm%\"")
                    ->orWhereRaw("folio LIKE \"%$this->searchTerm%\"")
                    ->orWhere('request', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('purchase', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('invoice', 'like', '%' . $this->searchTerm . '%');
                })
                ->orWhereHas('personal', function ($quer) {
                   $quer->whereRaw("name LIKE \"%$this->searchTerm%\"");
                })
                ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('comment', 'like', '%' . $this->searchTerm . '%');
            });
        }


        return null;
    }

    private function applySearchDeletedFilter($searchStation)
    {
        if ($this->searchTerm) {
            return $searchStation->onlyTrashed()
                    ->where(function (Builder $query) {
                $query->whereHas('order', function ($quer) {
                   $quer->whereRaw("comment LIKE \"%$this->searchTerm%\"")
                    ->orWhereRaw("folio LIKE \"%$this->searchTerm%\"")
                    ->orWhere('request', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('purchase', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('invoice', 'like', '%' . $this->searchTerm . '%');
                })
                ->orWhereHas('personal', function ($quer) {
                   $quer->whereRaw("name LIKE \"%$this->searchTerm%\"");
                })
                ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('comment', 'like', '%' . $this->searchTerm . '%');
            });
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
        $this->selectStatus = null;
        $this->statusName = '';
        $this->history = FALSE;
    }

    public function isHistory()
    {
        $this->resetPage();
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->currentMonth = FALSE;
        $this->currentWeek = FALSE;

        if($this->history){
            $this->history = FALSE;
        }
        else{
            $this->history = TRUE;
        }
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '10';
    }

    public function clearSelectStatus()
    {
        $this->selectStatus = null;
        $this->statusName = '';
    }

    public function updatedSelectStatus()
    {
        ($this->selectStatus === '') ? ($this->selectStatus = null) : (int) $this->selectStatus;
    
        if(isset($this->selectStatus)){
            $getStatus = Status::find($this->selectStatus);

            $this->statusName = $getStatus->name;
        }
        else{
            $this->statusName = '';
        }

        $this->clear();
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
            $station = Station::where('id', $id);
            $station->delete();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        $date = Carbon::now()->startOfMonth();
        return view('backend.station.livewire.station-table', [
            'stations' => $this->rows,
            'date' => $date,
        ]);
    }
}

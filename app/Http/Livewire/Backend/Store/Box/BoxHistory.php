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

    public bool $lastMonth = false;
    public bool $currentMonth = false;
    public bool $currentWeek = false;
    public bool $today = false;

    public $status;

    public bool $forExport = false;

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
        $query = Cash::query()
            ->whereNotNull('checked')
            ->when($this->dateInput, function ($query) {

                $this->isExportable();

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
                        ->orWhereRaw("id LIKE \"%$this->searchTerm%\"")
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
        $this->lastMonth = FALSE;
        $this->isExportable();
   }

    public function export()
    {
        $boxescollection = collect();

        foreach($this->rowsQuery->get() as $boxes){
            $boxescollection->push(
                $boxes->id,
            );
        }

        return redirect()->route('admin.store.printexport', urlencode(json_encode($boxescollection)));
    }

    public function isCurrentMonth()
    {
        $this->resetPage();
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->currentWeek = FALSE;
        $this->lastMonth = FALSE;
        $this->today = FALSE;

        if($this->currentMonth){
            $this->currentMonth = false;
        }
        else{
            $this->currentMonth = TRUE;
        }

        $this->isExportable();
    }

    public function isLastMonth()
    {
        $this->resetPage();
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->currentWeek = FALSE;
        $this->currentMonth = FALSE;
        $this->today = FALSE;

        if($this->lastMonth){
            $this->lastMonth = false;
        }
        else{
            $this->lastMonth = TRUE;
        }

        $this->isExportable();
    }

    public function isCurrentWeek()
    {
        $this->resetPage();
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->currentMonth = FALSE;
        $this->lastMonth = FALSE;
        $this->today = FALSE;

        if($this->currentWeek){
            $this->currentWeek = false;
        }
        else{
            $this->currentWeek = TRUE;
        }

        $this->isExportable();
    }

    public function isToday()
    {
        $this->resetPage();
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->currentMonth = FALSE;
        $this->currentWeek = FALSE;
        $this->lastMonth = FALSE;

        if($this->today){
            $this->today = false;
        }
        else{
            $this->today = TRUE;
        }

        $this->isExportable();
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

    public function updatedDeleted()
    {
        $this->resetPage();
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    /**
     * @return bool
     */
    public function isExportable(): bool
    {
        if($this->currentMonth)
            return $this->forExport = true;
        if($this->currentWeek)
            return $this->forExport = true;
        if($this->today)
            return $this->forExport = true;
        if($this->lastMonth)
            return $this->forExport = true;

        if($this->dateInput)
            return $this->forExport = true;

        return $this->forExport = false;
    }

    public function delete($id)
    {
        if($id)
            $box = Cash::where('id', $id)->first();

            foreach($box->finances as $finance){
                $finance->update(['cash_id' => null]);
            }

            foreach($box->orders as $orders){
                $orders->update(['cash_id' => null]);
            }

            $box->delete();

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
            'latest_box_history' => Cash::query()->latest('id')->whereNotNull('checked')->first() ?? null,
        ]);
    }
}

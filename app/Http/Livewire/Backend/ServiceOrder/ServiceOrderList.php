<?php

namespace App\Http\Livewire\Backend\ServiceOrder;

use Livewire\Component;
use App\Models\ServiceOrder;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ServiceOrderList extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'deleted' => ['except' => FALSE],
        'pending' => ['except' => FALSE],
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => '']
    ];

    public $perPage = '10';

    public $limitPerPage = '50';

    public $sortField = 'id';
    public $sortAsc = false;
    
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

    public bool $currentMonth = false;
    public bool $currentWeek = false;
    public bool $today = false;

    public bool $pending = false;

    public bool $history = false;

    public $status;

    public $personal;

    protected $listeners = ['filter' => 'filter', 'done', 'selectedCompanyItem', 'delete', 'restore', 'triggerRefresh' => '$refresh'];

    public $updated, $selected_id, $deleted;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->resetPage();
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function selectedCompanyItem($personal)
    {
        $this->personal = $personal;
    }

    public function getRowsQueryProperty()
    {
        $query = ServiceOrder::query()->with('personal', 'image', 'order.user', 'service_type', 'product_service_orders', 'createdby')
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                    $query->whereBetween('created_at', [$this->dateInput.' 00:00:00', now()]) :
                    $query->whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
            })
            ->when($this->personal, function($query){
                $query->where('user_id', $this->personal);
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
            })
            ->when(!$this->history, function ($query) {
                $query->where('done', false);
            })
            ;


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
            return $searchFinance->where(function(Builder $queryy) {
                $queryy->whereHas('personal', function ($query) {
                    $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
                 })
                ->orWhereHas('order', function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
                    })
                    ->orWhere('folio', 'like', '%' . $this->searchTerm . '%')
                    ;
                })
                ->orWhere('id', 'like', '%' . $this->searchTerm . '%');
            });
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
            return $this->rowsQuery->paginate(($this->perPage > $this->limitPerPage) ? $this->clear() : $this->perPage);
        });
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

    public function done(?int $id = null)
    {
        if($id){
            $finance = ServiceOrder::withTrashed()->find($id);
            
            $finance->update([
                'done' => $finance->done ? false : true,
                'approved' => $finance->done ? null : now(),
            ]);

            sleep(1);
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Changed'), 
        ]);
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

    public function clearPersonal()
    {
        $this->personal = null;
        $this->emit('clear-personal');
    }

    public function delete(int $id)
    {
        if($id)
            $serviceOrder = ServiceOrder::where('id', $id)->first();
            $serviceOrder->delete();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        $date = Carbon::now()->startOfMonth();

        return view('backend.serviceorder.service-order-list', [
            'serviceOrders' => $this->rows,
            'date' => $date,
        ]);
    }
}

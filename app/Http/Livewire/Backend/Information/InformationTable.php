<?php

namespace App\Http\Livewire\Backend\Information;

use Livewire\Component;
use App\Models\Status;
use App\Models\ProductStation;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class InformationTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'deleted' => ['except' => FALSE],
        'pending' => ['except' => FALSE],
        'history' => ['except' => FALSE],
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => '']
    ];

    public $perPage = '10';

    public $days = 31;

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
    public $status_id;
    public $personal;

    protected $listeners = ['filter' => 'filter', 'delete', 'restore', 'selectedCompanyItem', 'triggerRefresh' => '$refresh'];

    public $updated, $selected_id, $deleted;

    public function mount(Status $status)
    {
        $this->status_id = $status->id;
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

    protected function checkDateDifference()
    {
        if ($this->dateInput && $this->dateOutput) {
            $dateInput = Carbon::parse($this->dateInput);
            $dateOutput = Carbon::parse($this->dateOutput);

            if ($dateInput->diffInDays($dateOutput) > $this->days) {
                $this->dateInput = '';
                $this->dateOutput = '';
                $this->emit('swal:alert', [
                    'icon' => 'warning',
                    'title'   => __('Date range exceeds :days days and has been reset', ['days' => $this->days]),
                ]);
            }
            if($dateInput->gt($dateOutput)){
                $this->emit('swal:alert', [
                    'icon' => 'warning',
                    'title'   => 'Error en el rango',
                ]);
            }
        }
    }

    public function getRowsQueryProperty()
    {
        $query = ProductStation::query()->with('product', 'status')
            ->where('status_id', $this->status_id)
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
            })
            ->when(!$this->history, function ($query) {
                $query->where('active', true);
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


    public function selectedCompanyItem($personal)
    {
        $this->personal = $personal;

        // if ($customer) {
        //     $this->customer = $customer;

        //     $customerDB = User::where('id', $customer)->first();

        //     $summary = Summary::updateOrCreate(
        //         ['branch_id' => $this->branchId, 'type' => $this->type, 'user_id' => Auth::id()],
        //         ['customer_id' => $this->customer, 'type_price' => optional($customerDB->customer)->type_price ?? User::PRICE_RETAIL]
        //     );

        //      // $this->emit('updatePrices');
        // }
        // else{
        //     $this->customer = null;
        // }

        // $this->redirectLink();

    }

    private function applySearchFilter($searchProductStation)
    {
        if ($this->searchTerm) {
            return $searchProductStation
            ->where(function (Builder $query) {
                $query->whereHas('product.parent', function ($quer) {
                   $quer->whereRaw("name LIKE \"%$this->searchTerm%\"")->orWhereRaw("code LIKE \"%$this->searchTerm%\"");
                })
                ->orWhere('station_id', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('order_id', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return null;
    }

    private function applySearchDeletedFilter($searchProductStation)
    {
        if ($this->searchTerm) {
            return $searchProductStation->onlyTrashed()
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

    public function clearPersonal()
    {
        $this->personal = null;
        $this->emit('clear-personal');
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
        $this->history = FALSE;
        $this->clearPersonal();
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
        $this->checkDateDifference();

        $this->resetPage();
        $this->currentMonth = FALSE;
        $this->currentWeek = FALSE;
        $this->today = FALSE;
    }

    public function updatedDateOutput()
    {
        $this->checkDateDifference();
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
            $productStation = ProductStation::where('id', $id);
            $productStation->delete();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        $date = Carbon::now()->startOfMonth();
        return view('backend.information.livewire.information-table', [
            'productsStation' => $this->rows,
            'date' => $date,
        ]);
    }
}

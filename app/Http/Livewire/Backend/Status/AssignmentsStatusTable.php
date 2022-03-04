<?php

namespace App\Http\Livewire\Backend\Status;

use App\Models\Assignment;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class AssignmentsStatusTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => ''],
        'user' => ['except' => null],
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

    public $user;

    protected $listeners = ['filter' => 'filter', 'restore', 'refreshAssignmentTable' => '$refresh'];

    public $name, $short_name, $color, $secondary_color, $created, $updated, $selected_id;

    public function filter($user)
    {
        $this->resetPage();
        $this->user = $user;
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
        $query = Assignment::query()->with('assignmentable.product.color', 'assignmentable.product.size', 'user')
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

        if ($this->user !== null) {
            return $query->where('user_id', $this->user);
        }

        $this->applySearchFilter($query);

        return $query->where('status_id', $this->status);
    }

    private function applySearchFilter($searchAssigment)
    {
        if ($this->searchTerm) {
            return $searchAssigment->whereRaw("ticket_id LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("quantity LIKE \"%$this->searchTerm%\"");
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
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
        $this->user = null;
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

    public function render()
    {
        $date = Carbon::now()->startOfWeek();
        return view('backend.status.livewire.assignments-status-table', [
            'assignments' => $this->rows,
        ]);
    }
}

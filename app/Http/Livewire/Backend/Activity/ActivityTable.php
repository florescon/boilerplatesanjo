<?php

namespace App\Http\Livewire\Backend\Activity;

use Livewire\Component;
use Spatie\Activitylog\Models\Activity;
use App\Domains\Auth\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\ActivitiesExport;
use Excel;
use Illuminate\Validation\Rule;

class ActivityTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => '']
    ];

    public $perPage = '10';

    public $sortField = 'created_at';
    public $sortAsc = false;
    
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

    public $log_name;

    public function getRowsQueryProperty()
    {
        return Activity::query()
            ->where(function ($query) {
                $query->where('log_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('properties', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
            })
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                    $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', now()]) :
                    $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
            })
            ->when(!$this->dateInput, function ($query) {
                $query->whereYear('created_at', now()->year);
            })
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            });
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    public function updatedSearchTerm()
    {
        $this->page = 1;
    }

    public function updatedPerPage()
    {
        $this->page = 1;
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

    public function clear()
    {
        $this->searchTerm = '';
        $this->page = 1;
        $this->perPage = '10';
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
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function export()
    {
        return response()->streamDownload(function () {
            echo $this->selectedRowsQuery->toCsv();
        }, 'activities-list.csv');
    }

    private function getSelectedActivities()
    {
        return $this->selectedRowsQuery->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }
    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv', 'xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new ActivitiesExport($this->getSelectedActivities()), 'activities.'.$extension);
    }

    public function render()
    {
        return view('backend.activity.activity-table', [
            'activities' => $this->rows,
        ]);
    }
}

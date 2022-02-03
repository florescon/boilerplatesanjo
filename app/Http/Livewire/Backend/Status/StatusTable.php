<?php

namespace App\Http\Livewire\Backend\Status;

use App\Models\Status;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;


class StatusTable extends Component
{

    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'deleted' => ['except' => FALSE],
        'perPage',
    ];

    public $perPage = '10';

    public $sortField = 'level';
    public $sortAsc = true;

    public $status;
    public $searchTerm = '';

    public $deleted;

    /**
     * Assign users.
     *
     * @var bool
     */
    public bool $to_add_users = false;

    public function getRowsQueryProperty()
    {
        $query = Status::query()
                ->when($this->sortField, function ($query) {
                    $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
                });
        
        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        $this->applySearchFilter($query);

        return $query;

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


    private function applySearchFilter($statuses)
    {
        if ($this->searchTerm) {
            return $statuses->whereRaw("name LIKE \"%$this->searchTerm%\"");
        }

        return null;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '10';
    }


    public function clearAll()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '10';
    }


    public function render()
    {
        return view('backend.status.livewire.status-table', [
          'statuses' => $this->rows,
        ]);
    }
}

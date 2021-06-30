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

    public $sortField = 'name';
    public $sortAsc = true;

    public $status;
    public $searchTerm = '';

    public $deleted;



    public function getRowsQueryProperty()
    {
        $query = Status::query();
        
        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        $this->applySearchFilter($query);

        return $query;

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
        $this->page = 1;
        $this->perPage = '10';
    }


    public function clearAll()
    {
        $this->searchTerm = '';
        $this->page = 1;
        $this->perPage = '10';
    }


    public function render()
    {
        return view('backend.status.livewire.status-table', [
          'statuses' => $this->rows,
        ]);
    }
}

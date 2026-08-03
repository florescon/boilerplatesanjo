<?php

namespace App\Http\Livewire\Backend\Processes;

use App\Models\Process;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;

class ProcessTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'deleted' => ['except' => FALSE],
        'perPage',
    ];

    protected $listeners = ['triggerRefresh' => '$refresh', 'delete' => '$refresh', 'restore' => '$refresh'];

    public $perPage = '20';

    public $sortField = 'name';
    public $sortAsc = true;

    public $process;
    public $searchTerm = '';

    public $deleted;

    public $status;

    /**
     * Assign users.
     *
     * @var bool
     */
    public bool $to_add_users = false;

    public function getRowsQueryProperty()
    {
        $query = Process::query()
            ->with(['routes.operation'])
            ->when($this->sortField, function ($query) {
                $query->orderBy(
                    $this->sortField,
                    $this->sortAsc ? 'asc' : 'desc'
                );
            });

        if ($this->process === 'deleted') {
            return $query->onlyTrashed();
        }

        $this->applySearchFilter($query);

        return $query;
    }

    public function active(?int $id = null)
    {
        if($id){
            $vendor = Process::find($id);
            
            $vendor->update([
                'is_active' => $vendor->is_active ? false : true,
            ]);

            sleep(1);
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Changed'), 
        ]);
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


    private function applySearchFilter($processes)
    {
        if ($this->searchTerm) {
            return $processes->whereRaw("name LIKE \"%$this->searchTerm%\"");
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
        $this->perPage = '20';
    }


    public function clearAll()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '20';
    }

    public function render()
    {
        return view('backend.processes.livewire.process-table', [
          'processes' => $this->rows,
        ]);
    }
}

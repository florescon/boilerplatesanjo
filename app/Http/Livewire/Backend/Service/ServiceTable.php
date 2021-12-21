<?php

namespace App\Http\Livewire\Backend\Service;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;

class ServiceTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    public $perPage = '12';

    public $status;
    public $searchTerm = '';

    protected $listeners = ['triggerRefresh' => '$refresh', 'delete' => '$refresh', 'restore' => '$refresh'];

    public function getRowsQueryProperty()
    {
        $query = Product::query()
            ->onlyServices()
            ->orderBy('updated_at', 'desc');

        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        $this->applySearchFilter($query);

        return $query;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    private function applySearchFilter($services)
    {
        if ($this->searchTerm) {
            return $services->whereRaw("code LIKE \"%$this->searchTerm%\"")
                            ->orWhereRaw("name LIKE \"%$this->searchTerm%\"")
                            ->orWhereRaw("description LIKE \"%$this->searchTerm%\"");
        }

        return null;
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->page = 1;
        $this->perPage = '12';
    }

    public function updatedSearchTerm()
    {
        $this->page = 1;
    }

    public function updatedPerPage()
    {
        $this->page = 1;
    }

    public function restore($id)
    {
        if($id){
            Product::withTrashed()->find($id)->restore();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }

    public function delete(Product $service)
    {
        if($service)
            $service->delete();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        return view('backend.service.table.service-table', [
            'services' => $this->rows,
        ]);
    }
}

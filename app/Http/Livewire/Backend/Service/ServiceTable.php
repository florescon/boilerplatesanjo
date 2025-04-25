<?php

namespace App\Http\Livewire\Backend\Service;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Events\Service\ServiceUpdated;
use App\Events\Service\ServiceDeleted;
use App\Events\Service\ServiceRestored;

class ServiceTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    public $perPage = '12';

    public $limitPerPage = '100';

    public $name, $price, $code, $is_active;

    public $created, $updated, $deleted, $selected_id;

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
            return $this->rowsQuery->paginate(($this->perPage > $this->limitPerPage) ? $this->clear() : $this->perPage);
        });
    }

    private function applySearchFilter($services)
    {
        if ($this->searchTerm) {
            return $services->whereRaw("code LIKE \"%$this->searchTerm%\"")
                            ->orWhereRaw("name LIKE \"%$this->searchTerm%\"")
                            ->orWhereRaw("description LIKE \"%$this->searchTerm%\"")
                            ->onlyServices();
        }

        return null;
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '12';
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function show($id)
    {
        $record = Product::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->code = $record->code;
        $this->price = $record->price;
        $this->is_active = $record->status_name;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function edit($id)
    {
        $record = Product::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->code = $record->code;
        $this->price = $record->price;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->code = '';
        $this->price = '';
    }

    public function update()
    {
        $this->validate([
            'selected_id' => ['required', 'numeric'],
            'name' => ['required', 'min:3'],
            'code' => ['required', 'min:3', 'max:15', 'regex:/^\S*$/u', Rule::unique('products')->ignore($this->selected_id)],
            'price' => ['required', 'numeric'],
        ]);

        if ($this->selected_id) {
            $service = Product::find($this->selected_id);
            $service->update([
                'name' => $this->name,
                'code' => $this->code ?: null,
                'price' => $this->price
            ]);
            $this->resetInputFields();
        }

        event(new ServiceUpdated($service));

        $this->emit('serviceUpdate');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated'), 
        ]);
    }

    public function restore(int $id)
    {
       if($id){
            $restore_service = Product::withTrashed()
                ->where('id', $id)
                ->first();

            event(new ServiceRestored($restore_service));

            $restore_service->restore();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }

    public function delete(Product $service)
    {
        if($service){
            event(new ServiceDeleted($service));
            $service->delete();
        }

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

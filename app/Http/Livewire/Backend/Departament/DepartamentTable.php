<?php

namespace App\Http\Livewire\Backend\Departament;

use Livewire\Component;
use App\Models\Departament;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\DepartamentsExport;
use Excel;
use Illuminate\Validation\Rule;

class DepartamentTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'deleted' => ['except' => FALSE],
    ];

    public $perPage = '10';

    public $sortField = 'name';
    public $sortAsc = true;
    
    public $searchTerm = '';

    protected $listeners = ['delete' => '$refresh', 'restore' => '$refresh'];

    public $name, $email, $comment, $is_enabled, $is_disabled;

    public $created, $updated, $deleted, $selected_id;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|min:3|regex:/^\S*$/u|unique:departaments',
        'comment' => 'sometimes|min:3',
    ];

    public function getRowsQueryProperty()
    {
        
        return Departament::query()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('comment', 'like', '%' . $this->searchTerm . '%');
            })
            ->when($this->deleted, function ($query) {
                $query->onlyTrashed();
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedSearchTerm()
    {
        $this->page = 1;
    }

    public function updatedPerPage()
    {
        $this->page = 1;
    }

    public function updatedDeleted()
    {
        $this->page = 1;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
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

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        $validatedData = $this->validate();

        Departament::create($validatedData);

        $this->resetInputFields();
        $this->emit('departamentStore');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);
    }

    public function edit($id)
    {
        $record = Departament::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->email = $record->email;
        $this->comment = $record->comment;
    }

    public function show($id)
    {
        $record = Departament::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->email = $record->email;
        $this->comment = $record->comment;
        $this->is_enabled = $record->is_enabled_departament;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => ['required', 'numeric'],
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'min:3', 'regex:/^\S*$/u', Rule::unique('departaments')->ignore($this->selected_id)],
            'comment' => ['sometimes', 'nullable'],
        ]);

        if ($this->selected_id) {
            $record = Departament::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'email' => $this->email,
                'comment' => $this->comment
            ]);
            $this->resetInputFields();
        }

        $this->emit('departamentUpdate');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated'), 
        ]);
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->comment = '';
    }

    public function export()
    {
        return response()->streamDownload(function () {
            echo $this->selectedRowsQuery->toCsv();
        }, 'departament-list.csv');
    }

    private function getSelectedDepartaments()
    {
        return $this->selectedRowsQuery->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }
    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv', 'xlsx', 'pdf', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new DepartamentsExport($this->getSelectedDepartaments()), 'departaments.'.$extension);
    }

    public function restore($id)
    {
        if($id){
            $restore_color = Departament::withTrashed()
                ->where('id', $id)
                ->restore();
        }

      $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }

    public function enable(Departament $departament)
    {
        if($departament)
            $departament->update([
                'is_enabled' => true
            ]);

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Enabled'), 
        ]);
    }

    public function disable(Departament $departament)
    {
        if($departament)
            $departament->update([
                'is_enabled' => false
            ]);

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Disabled'), 
        ]);
    }

    public function delete(Departament $departament)
    {
        if($departament)
            $departament->delete();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        return view('backend.departament.table.departament-table', [
            'departaments' => $this->rows,
        ]);
    }
}
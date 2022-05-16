<?php

namespace App\Http\Livewire\Backend\Departament;

use Livewire\Component;
use App\Models\Departament;
use App\Domains\Auth\Models\User;
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

    public ?string $phone = null;
    public ?string $address = null;
    public ?string $rfc = null;

    public ?string $type_price = User::PRICE_RETAIL;

    public $created, $updated, $deleted, $selected_id;

    protected $rules = [
        'name' => ['required', 'min:3'],
        'email' => ['required', 'email', 'min:3', 'regex:/^\S*$/u', 'unique:departaments'],
        'comment' => ['nullable', 'min:3', 'max:100'],
        'phone' => ['nullable', 'digits:10'],
        'address' => ['sometimes', 'max:100'],
        'rfc' => ['sometimes', 'max:50'],
        'type_price' => ['nullable'],
    ];

    public function getRowsQueryProperty()
    {
        
        return Departament::query()->with('user')
            ->where(function ($query) {
                 $query->whereHas('user', function($query) {
                    $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
                })->orWhere('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('phone', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('address', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('rfc', 'like', '%' . $this->searchTerm . '%');
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
        $this->resetPage();
    }

    public function updatedPerPage()
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
        $this->resetPage();
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
        $this->phone = $record->phone;
        $this->address = $record->address;
        $this->rfc = $record->rfc;
        $this->type_price = $record->type_price;
    }

    public function show($id)
    {
        $record = Departament::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->email = $record->email;
        $this->comment = $record->comment;
        $this->phone = $record->phone;
        $this->address = $record->address;
        $this->rfc = $record->rfc;
        $this->is_enabled = $record->is_enabled_departament;
        $this->created = $record->created_at_formatted;
        $this->updated = $record->updated_at_formatted;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => ['required', 'numeric'],
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'min:3', 'regex:/^\S*$/u', Rule::unique('departaments')->ignore($this->selected_id)],
            'comment' => ['sometimes', 'nullable'],
            'phone' => ['nullable', 'digits:10'],
            'address' => ['sometimes', 'max:100'],
            'rfc' => ['sometimes','max:50'],
            'type_price' => ['required', Rule::in([User::PRICE_RETAIL, User::PRICE_AVERAGE_WHOLESALE, User::PRICE_WHOLESALE])],
        ]);

        if ($this->selected_id) {
            $record = Departament::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'email' => $this->email,
                'comment' => $this->comment,
                'phone' => $this->phone,
                'address' => $this->address,
                'rfc' => $this->rfc,
                'type_price' => $this->type_price,
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
        abort_if(!in_array($extension, ['csv', 'xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
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
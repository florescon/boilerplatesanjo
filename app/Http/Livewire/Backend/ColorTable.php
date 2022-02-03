<?php

namespace App\Http\Livewire\Backend;

use App\Models\Color;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\ColorsExport;
use Excel;
use Illuminate\Validation\Rule;
use App\Events\Color\ColorCreated;
use App\Events\Color\ColorUpdated;
use App\Events\Color\ColorDeleted;
use App\Events\Color\ColorRestored;

class ColorTable extends Component
{
	use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

	protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'deleted' => ['except' => FALSE],
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => '']
    ];

    public $perPage = '10';

    public $sortField = 'name';
    public $sortAsc = true;
    
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

    protected $listeners = ['delete', 'restore' => '$refresh'];

    public $name, $short_name, $color, $secondary_color, $created, $updated, $selected_id, $deleted;

    protected $rules = [
        'name' => 'required|min:3|max:20',
        'short_name' => 'required|min:3|max:6|regex:/^\S*$/u|unique:colors',
        'color' => 'required|unique:colors',
        'secondary_color' => '',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
        return Color::query()->with('product', 'products')
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                    $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', now()]) :
                    $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
            })
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('short_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('slug', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('color', 'like', '%' . $this->searchTerm . '%');
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

    public function render()
    {
        return view('backend.color.table.color-table', [
            'colors' => $this->rows,
        ]);
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
        $this->resetPage();
        $this->perPage = '10';
        $this->deleted = FALSE;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
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

    public function updatedDeleted()
    {
        // $colors = Product::all();

        // foreach($colors as $color){
            // $name = $color->name;
            // $color->update(['name' => 'a'.$name]);
            // $color->update(['name' => substr($name, 1)]);
        // }

        $this->resetPage();
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        $validatedData = $this->validate();

        $color = Color::create($validatedData);

        event(new ColorCreated($color));

        $this->resetInputFields();
        $this->emit('colorStore');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);
        // session()->flash('message-success', __('The color was successfully created.'));
    }

    public function edit(int $id)
    {
        $this->resetValidation();

        $record = Color::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record->name;
        $this->short_name = $record->short_name;
        $this->color = $record->color;
    }

    public function show(int $id)
    {
        $record = Color::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->color = $record->color;
        $this->secondary_color = $record->secondary_color;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:3|max:20',
            'short_name' => ['required', 'min:3', 'max:6', 'regex:/^\S*$/u', Rule::unique('colors')->ignore($this->selected_id)],
            'color' => 'required',
            'secondary_color' => ''
        ]);
        if ($this->selected_id) {
            $color = Color::find($this->selected_id);
            $color->update([
                'name' => $this->name,
                'short_name' => $this->short_name,
                'color' => $this->color,
                'secondary_color' => $this->secondary_color
            ]);
            $this->resetInputFields();
        }

        event(new ColorUpdated($color));

        $this->emit('colorUpdate');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated'), 
        ]);
    }

    private function resetInputFields()
    {
        $this->resetValidation();
        $this->name = '';
        $this->color = '';
        $this->short_name = '';
    }

    public function export()
    {
        return response()->streamDownload(function () {
            echo $this->selectedRowsQuery->toCsv();
        }, 'color-list.csv');
    }

    private function getSelectedColors()
    {
        return $this->selectedRowsQuery->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv', 'xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new ColorsExport($this->getSelectedColors()), 'colors.'.$extension);
    }

    public function delete(Color $color)
    {
        if($color){
            event(new ColorDeleted($color));
            $color->delete();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function restore(int $id)
    {
        if($id){
            $restore_color = Color::withTrashed()
                ->where('id', $id)
                ->first();

            event(new ColorRestored($restore_color));

            $restore_color->restore();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }
}
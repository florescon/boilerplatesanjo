<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use App\Exports\ParentMaterialsExport;
use Excel;
use DB;

class AssociatesMaterials extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    public $attributeID;
    public $name;
    public $link;
    public $model;

    protected $paginationTheme = 'bootstrap';

    public $perPage = '10';

    public $searchTerm = '';

    public $sortField = 'updated_at';

    public $sortAsc = false;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];
 
    public function mount($attribute, string $link, $nameModel)
    {
        $this->attributeID = $attribute->id;
        $this->name = $attribute->name;
        $this->link = $link;
        $this->model = '\\App\\Models\\'.$nameModel;
    }

    public function getRowsQueryProperty()
    {
        $attribute = $this->model::findOrFail($this->attributeID);
        
        $query = $attribute->materials()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('part_number', 'like', '%' . $this->searchTerm . '%');
            });

        return $query;
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

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function export()
    {
        return response()->streamDownload(function () {
            echo $this->selectedRowsQuery->toCsv();
        }, 'color-list.csv');
    }

    private function getSelectedMaterials()
    {
        return $this->selectedRowsQuery->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new ParentMaterialsExport($this->getSelectedMaterials()), 'material-list-'.Carbon::now().'.'.$extension);
    }

    public function render()
    {
        $attribute = $this->model::findOrFail($this->attributeID);

        $associates = $attribute->materials()->paginate(10);

        return view('backend.material.livewire.associates-materials',[
            'attribute' => $attribute,
            'associates' => $this->rows,
        ]);
    }
}

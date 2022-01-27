<?php

namespace App\Http\Livewire\Backend;

use App\Models\Material;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Events\Material\MaterialDeleted;
use App\Events\Material\MaterialRestored;

class MaterialTable extends TableComponent
{
    use HtmlComponents;

    use WithPagination;

    public $search;

    public $status;

    public $perPage = '10';

    public $editStock = false;

    public $searchDebounce = 1024;

    public $tableFooterEnabled = true;

    public $perPageOptions = ['10', '25', '50', '100'];

    public $exports = ['csv', 'xls', 'xlsx'];
    public $exportFileName = 'feedstocks';

    public $clearSearchButton = true;
    
    protected $queryString = [
        'search' => ['except' => ''], 
        'editStock' => ['except'=> false],
        'perPage',
    ];

    protected $listeners = ['postAdded' => 'updateEditStock', 'delete', 'restore', 'triggerRefresh' => '$refresh'];

    /**
     * @var string
     */
    public $sortField = 'name';

    /**
     * @var array
     */
    protected $options = [
        'bootstrap.container' => false,
        'bootstrap.classes.table' => 'table table-striped-orange table-bordered',
        'bootstrap.classes.thead' => 'thead-dark border-bottom-3px-orange',
        'bootstrap.responsive' => true,
    ];

    /**
     * @param  string  $status
     */
    public function mount($status = 'active'): void
    {
        $this->status = $status;
    }

    public function updateEditStock()
    {
        $this->editStock = !$this->editStock;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = Material::query()->with('color', 'size', 'unit');

        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        return $query;
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('Part number'), 'part_number')
                ->searchable()
                ->sortable(),
            Column::make(__('Name'), 'name')
                ->searchable()
                ->sortable(),
            Column::make(__('Unit'), 'unit.name')
                ->searchable()
                ->format(function(Material $model) {
                    return $this->html(!empty($model->unit_id) && isset($model->unit->id) ? $model->unit->name : '<span class="badge badge-pill badge-secondary"> <em>No asignada</em></span>');
                }),
            Column::make(__('Color'), 'color.name')
                ->searchable()
                ->format(function(Material $model) {
                    return $this->html(!empty($model->color_id) && isset($model->color->id) ? '<i style="border-bottom: 1px solid; font-size:14px; color:'.(optional($model->color)->color ?  optional($model->color)->color : 'transparent').';" class="fa">&#xf0c8;</i> '. optional($model->color)->name : '<span class="badge badge-pill badge-secondary"> <em>No definido</em></span>');
                })
                ->exportFormat(function(Material $model) {
                    return (!empty($model->color_id) && isset($model->color->id)) ? optional($model->color)->name : '';
                }),
            Column::make(__('Size_'), 'size.name')
                ->searchable()
                ->format(function(Material $model) {
                    return $this->html(!empty($model->size_id) && isset($model->size->id) ? $model->size->name : '<span class="badge badge-pill badge-secondary"> <em>No asignada</em></span>');
                })
                ->exportFormat(function(Material $model) {
                    return (!empty($model->size_id) && isset($model->size->id)) ? optional($model->size)->name : '';
                }),
            Column::make(__('Price'), 'price')
                ->searchable()
                ->sortable(),
            Column::make(__('Stock'), 'stock')
                ->searchable()
                ->sortable()
                ->format(function(Material $model) {
                    return $this->html($model->stock_formatted);
                })
                ->exportFormat(function(Material $model) {
                    return $model->stock_formatted;
                }),
            Column::make(__('Updated at'), 'updated_at')
                ->searchable()
                ->sortable()
                ->hideIf($this->editStock == true),
            Column::make(__('Actions'))
                ->format(function (Material $model) {
                    return view('backend.material.datatable.actions', ['material' => $model]);
                })
                ->excludeFromExport()
                ->hideIf($this->editStock == true),
            Column::make(__('Add / Subtract'))
                ->format(function (Material $model) {
                    return view('backend.material.datatable.input', ['material' => $model]);
                })
                ->excludeFromExport()
                ->hideIf($this->editStock == false),
        ];
    }

    public function delete(Material $material)
    {
        if($material){
            event(new MaterialDeleted($material));
            $material->delete();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function restore(int $id)
    {
       if($id){
            $restore_material = Material::withTrashed()
                ->where('id', $id)
                ->first();

            event(new MaterialRestored($restore_material));

            $restore_material->restore();
        }

      $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }
}

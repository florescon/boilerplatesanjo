<?php

namespace App\Http\Livewire\Backend;

use App\Models\Material;
use App\Models\Family;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Events\Material\MaterialDeleted;
use App\Events\Material\MaterialRestored;
use DB;

class MaterialTable extends TableComponent
{
    use HtmlComponents;

    use WithPagination;

    public $search;

    public $status;

    public $perPage = '10';

    public $editStock = false;

    public $massAssginment = false;

    public $searchDebounce = 1024;

    public $tableFooterEnabled = true;

    public $perPageOptions = ['10', '25', '50', '100'];

    public $exports = ['csv', 'xls', 'xlsx'];
    public $exportFileName = 'feedstocks';

    public $selectedMassive = [];

    public $family_id, $vendor_id, $color_id;

    public $clearSearchButton = true;
    
    protected $queryString = [
        'search' => ['except' => ''], 
        'editStock' => ['except'=> false],
    ];

    protected $listeners = ['postAdded' => 'updateEditStock', 'postMassive' => 'updateMassAssignment', 'emitFamily', 'emitVendor', 'emitColor', 'delete', 'restore', 'triggerRefresh' => '$refresh'];

    /**
     * @var string
     */
    public $sortField = 'family_id';

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

    public function updateMassAssignment()
    {
        // dd($this->selectedMassive);
        $this->emit('massivemodal', $this->selectedMassive);
        $this->massAssginment = !$this->massAssginment;
    }

    public function emitFamily($family)
    {
        $this->resetPage();
        $this->family_id = $family;
    }

    public function emitVendor($vendor)
    {
        $this->resetPage();
        $this->vendor_id = $vendor;
    }

    public function emitColor($color)
    {
        $this->resetPage();
        $this->color_id = $color;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = Material::query()->with('color', 'size', 'unit', 'vendor', 'family');

        if($this->family_id){
            $family_id = $this->family_id;
            $query->whereHas('family', function($queryFamily) use ($family_id){
                $queryFamily->where('family_id', $family_id);
            });
        }

        if($this->vendor_id){
            $vendor_id = $this->vendor_id;
            $query->whereHas('vendor', function($queryFamily) use ($vendor_id){
                $queryFamily->where('vendor_id', $vendor_id);
            });
        }

        if($this->color_id){
            $color_id = $this->color_id;
            $query->whereHas('color', function($queryFamily) use ($color_id){
                $queryFamily->where('color_id', $color_id);
            });
        }

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
            Column::make(__('C.'))
                ->format(function (Material $model) {
                    return view('backend.material.datatable.massive', ['model' => $model]);
                })
                ->excludeFromExport(),
            Column::make(__('Code'), 'part_number')
                ->searchable()
                ->sortable(),
            Column::make(__('Stock'), 'stock')
                ->searchable()
                ->sortable()
                ->format(function(Material $model) {
                    return $this->html('<strong>'.$model->stock_formatted.'</strong> '.$model->unit_name_label );
                })
                ->exportFormat(function(Material $model) {
                    return $model->stock_formatted;
                })
                ->hideIf(auth()->user()->cannot('admin.access.material.show-quantities')),
            Column::make(__('Unit'), 'unit.name')
                ->exportFormat(function(Material $model) {
                    return $model->unit_id ? optional($model->unit)->name : '--';
                })
                ->exportOnly(),
            Column::make(__('Name'), 'name')
                ->searchable()
                ->format(function(Material $model) {
                    return $this->html(!empty($model->family_id) && isset($model->family->name) ? '<strong>'.$model->name.'</strong><br>'.$model->family->name_label : '<strong>'.$model->name.'</strong>');
                })
                ->exportFormat(function(Material $model) {
                    return $model->name ?? '--';
                })
                ->sortable(),
            Column::make(__('Color'), 'color.name')
                ->searchable()
                ->format(function(Material $model) {
                    return $this->html(!empty($model->color_id) && isset($model->color->id) ? '<i style="border-bottom: 1px solid; font-size:14px; color:'.(optional($model->color)->color ?  optional($model->color)->color : 'transparent').';" class="fa">&#xf0c8;</i> <strong>'. optional($model->color)->name.'</strong>' : '<span class="badge badge-pill badge-secondary"> <em>No definido</em></span>');
                })
                ->exportFormat(function(Material $model) {
                    return (!empty($model->color_id) && isset($model->color->id)) ? optional($model->color)->name : '';
                }),
            Column::make(__('Price'), 'price')
                ->searchable()
                ->sortable()
                ->hideIf(auth()->user()->cannot('admin.access.material.show-prices')),
            Column::make(__('Description'), 'description')
                ->searchable()
                ->sortable()
                ->format(function(Material $model) {
                    return $this->html($model->description ?? '--');
                }),
            Column::make(__('Vendor'), 'vendor.name')
                ->searchable()
                ->format(function(Material $model) {
                    return $this->html(!empty($model->vendor_id) && isset($model->vendor->id) ? (optional($model->vendor)->short_name ?? optional($model->vendor)->name) : '<span class="badge badge-pill badge-secondary"> <em>Proveedor no definido</em></span>');
                })
                ->exportFormat(function(Material $model) {
                    return (!empty($model->vendor_id) && isset($model->vendor->id)) ? (optional($model->vendor)->short_name ?? optional($model->vendor)->name) : '';
                })
                ->sortable(),
            Column::make(__('Family'), 'family.name')
                ->searchable()
                ->format(function(Material $model) {
                    return $this->html(!empty($model->family_id) && isset($model->family->id) ? (optional($model->family)->short_name ?? optional($model->family)->name) : '<span class="badge badge-pill badge-secondary"> <em>Familia no definida</em></span>');
                })
                ->sortable()
                ->exportFormat(function(Material $model) {
                    return (!empty($model->family_id) && isset($model->family->id)) ? optional($model->family)->name : '';
                }),
            Column::make(__('Actions'))
                ->format(function (Material $model) {
                    return view('backend.material.datatable.actions', ['model' => $model]);
                })
                ->excludeFromExport()
                ->hideIf($this->editStock == true or $this->massAssginment == true),
            // Column::make(__('Updated at'), 'updated_at')
            //     ->searchable()
            //     ->sortable()
            //     ->hideIf($this->editStock == true),
            // Column::make(__('Mass assignment'))
            //     ->format(function (Material $model) {
            //         return view('backend.material.datatable.mass', ['model' => $model]);
            //     })
            //     ->excludeFromExport()
            //     ->hideIf($this->massAssginment == false),
            Column::make(__('Input / Output'))
                ->format(function (Material $model) {
                    return view('backend.material.datatable.input', ['model' => $model]);
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
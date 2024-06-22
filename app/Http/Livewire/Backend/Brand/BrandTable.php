<?php

namespace App\Http\Livewire\Backend\Brand;

use App\Models\Brand;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Events\Brand\BrandDeleted;
use App\Events\Brand\BrandRestored;

class BrandTable extends TableComponent
{
    use HtmlComponents, WithPagination;

    public $search;

    public $status;

    public $perPage = '10';

    public $tableFooterEnabled = true;

    public $perPageOptions = ['10', '25', '50'];

    public $exports = ['csv', 'xls', 'xlsx'];
    public $exportFileName = 'brands';

    public $clearSearchButton = true;
    
    protected $queryString = [
        'search' => ['except' => ''], 
        'perPage',
    ];

    protected $listeners = ['internal', 'delete', 'restore', 'triggerRefresh' => '$refresh'];

    /**
     * @var string
     */
    public $sortField = 'updated_at';

    public $sortDirection = 'desc';

    /**
     * @var array
     */
    protected $options = [
        'bootstrap.container' => false,
        'bootstrap.classes.table' => 'table table-striped table-bordered',
        'bootstrap.classes.thead' => 'thead-dark border-bottom-3px',
        'bootstrap.responsive' => true,
    ];

    /**
     * @param  string  $status
     */
    public function mount($status = 'active'): void
    {
        $this->status = $status;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = Brand::query()->with('products');

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
            Column::make(__('Name'), 'name')
                ->searchable()
                ->sortable(),
            Column::make(__('Website'), 'website')
                ->searchable()
                ->sortable()
                ->format(function(Brand $model) {
                    return $this->html($model->website ? '<a href="'.$model->website.'" target="_blank">'.$model->website .'</a>': '<span class="badge badge-secondary">'.__('undefined').'</span>');
                }),
            Column::make(__('Slug'), 'slug')
                ->searchable()
                ->sortable()
                ->format(function(Brand $model) {
                    return $this->html($model->slug ?: '<span class="badge badge-secondary">'.__('undefined').'</span>');
                })
                ->exportFormat(function(Brand $model) {
                    return $model->slug;
                }),
            Column::make('# '.__('Associated products'), 'count_products')
                ->format(function(Brand $model) {
                    return $this->link(route('admin.brand.associates', $model->id), $model->count_products);
                }),
            Column::make(__('Internal brand'), 'is_internal')
                ->format(function (Brand $model) {
                    return view('backend.brand.datatable.internal', ['brand' => $model]);
                })
                ->excludeFromExport(),
            Column::make(__('Created at'), 'created_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Updated at'), 'updated_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Actions'))
                ->format(function (Brand $model) {
                    return view('backend.brand.datatable.actions', ['brand' => $model]);
                })
                ->excludeFromExport(),
        ];
    }

    public function internal(?int $id = null)
    {
        if($id){
            $brand = Brand::withTrashed()->find($id);
            
            $brand->update([
                'is_internal' => $brand->is_internal ? false : true,
            ]);

            sleep(1);
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Changed'), 
        ]);
    }


    public function delete(Brand $brand)
    {
        if($brand){
            event(new BrandDeleted($brand));
            $brand->delete();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function restore(?int $id = null)
    {
        if($id){
            $restore_brand = Brand::withTrashed()
                ->where('id', $id)
                ->first();

            event(new BrandRestored($restore_brand));

            $restore_brand->restore();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }
}
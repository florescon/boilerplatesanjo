<?php

namespace App\Http\Livewire\Backend\Size;

use App\Models\Size;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Events\Size\SizeDeleted;
use App\Events\Size\SizeRestored;

class SizeTable extends TableComponent
{
    use HtmlComponents, WithPagination;

    public $search;

    public $status;

    public $perPage = '10';

    public $tableFooterEnabled = true;

    public $perPageOptions = ['10', '25', '50', '100'];

    public $exports = ['csv', 'xls', 'xlsx'];
    public $exportFileName = 'sizes';

    public $clearSearchButton = true;
    
    protected $queryString = [
        'search' => ['except' => ''], 
        'perPage',
    ];

    protected $listeners = ['delete', 'restore', 'triggerRefresh' => '$refresh'];

    /**
     * @var string
     */
    public $sortField = 'sort';

    public $sortDirection = 'asc';

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
        $query = Size::query()->with('products');

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
            Column::make(__('Coding'), 'short_name')
                ->searchable()
                ->sortable()
                ->format(function(Size $model) {
                    return $this->html($model->short_name ?: '<span class="badge badge-secondary">'.__('undefined').'</span>');
                })
                ->exportFormat(function(Size $model) {
                    return $model->short_name;
                }),
            Column::make(__('Slug'), 'slug')
                ->searchable()
                ->sortable()
                ->format(function(Size $model) {
                    return $this->html($model->slug ?: '<span class="badge badge-secondary">'.__('undefined').'</span>');
                })
                ->excludeFromExport(),
            Column::make('# '.__('Associated products'), 'count_products')
                ->format(function(Size $model) {
                    return $this->link(route('admin.size.associates', $model->id), $model->count_products);
                }),
            // Column::make('# '.__('Associated subproducts'), 'count_products')
            //     ->format(function(Size $model) {
            //         return $this->link(route('admin.size.associates_sub', $model->id), $model->count_products);
            //     }),
            Column::make(__('Sort'), 'sort')
                ->searchable()
                ->sortable()
                ->excludeFromExport(),
            Column::make(__('Created at'), 'created_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Updated at'), 'updated_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Actions'))
                ->format(function (Size $model) {
                    return view('backend.size.datatable.actions', ['size' => $model]);
                })
                ->excludeFromExport(),
        ];
    }

    public function delete(Size $size)
    {

        event(new SizeDeleted($size));

        $size->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function restore(?int $id = null)
    {
        if($id){
            $restore_size = Size::withTrashed()->find($id);

            event(new SizeRestored($restore_size));

            $restore_size->restore();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }
}
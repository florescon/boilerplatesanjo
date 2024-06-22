<?php

namespace App\Http\Livewire\Backend\ServiceType;

use App\Models\ServiceType;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ServiceTable extends TableComponent
{
    use HtmlComponents, WithPagination;

    public $search;

    public $status;

    public $perPage = '10';

    public $tableFooterEnabled = true;

    public $perPageOptions = ['10', '25', '50'];

    public $exports = ['csv', 'xls', 'xlsx'];
    public $exportFileName = 'servicetype';

    public $clearSearchButton = true;
    
    protected $queryString = [
        'search' => ['except' => ''], 
    ];

    protected $listeners = ['delete', 'restore', 'triggerRefresh' => '$refresh'];

    /**
     * @var string
     */
    public $sortField = 'name';

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
        $query = ServiceType::query();

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
            Column::make(__('Slug'), 'slug')
                ->searchable()
                ->sortable()
                ->format(function(ServiceType $model) {
                    return $this->html($model->slug ?: '<span class="badge badge-secondary">'.__('undefined').'</span>');
                })
                ->excludeFromExport(),
            Column::make(__('Created at'), 'created_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Updated at'), 'updated_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Actions'))
                ->format(function (ServiceType $model) {
                    return view('backend.servicetype.datatable.actions', ['servicetype' => $model]);
                })
                ->excludeFromExport(),
        ];
    }

    public function delete(ServiceType $servicetype)
    {
        $servicetype->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function restore(?int $id = null)
    {
        if($id){
            $restore_servicetype = ServiceType::withTrashed()->find($id);

            $restore_servicetype->restore();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }
}

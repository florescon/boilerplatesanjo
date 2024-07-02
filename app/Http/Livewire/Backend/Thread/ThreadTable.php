<?php

namespace App\Http\Livewire\Backend\Thread;

use App\Models\Thread;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ThreadTable extends TableComponent
{
    use HtmlComponents, WithPagination;

    public $search;

    public $status;

    public $perPage = '10';

    public $tableFooterEnabled = true;

    public $perPageOptions = ['10', '25', '50'];

    public $exports = ['csv', 'xls', 'xlsx'];
    public $exportFileName = 'threads';

    public $clearSearchButton = true;
    
    protected $queryString = [
        'search' => ['except' => ''], 
        'perPage',
    ];

    protected $listeners = ['delete', 'restore', 'triggerRefresh' => '$refresh'];

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
        $query = Thread::query();

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
            Column::make(__('Code'), 'code')
                ->searchable()
                ->sortable(),
            Column::make(__('Brand'), 'brand.name')
                ->searchable()
                ->format(function(Thread $model) {
                    return $this->html(!empty($model->brand_id) && isset($model->brand->id) ? (optional($model->brand)->short_name ?? optional($model->brand)->name) : '<span class="badge badge-pill badge-secondary"> <em>Proveedor no definido</em></span>');
                })
                ->exportFormat(function(Thread $model) {
                    return (!empty($model->brand_id) && isset($model->brand->id)) ? (optional($model->brand)->short_name ?? optional($model->brand)->name) : '';
                }),
            Column::make(__('Created at'), 'created_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Updated at'), 'updated_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Actions'))
                ->format(function (Thread $model) {
                    return view('backend.thread.datatable.actions', ['thread' => $model]);
                })
                ->excludeFromExport(),
        ];
    }

    public function delete(Thread $thread)
    {
        if($thread){
            $thread->delete();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function restore(int $id)
    {
       if($id){
            $restore_thread = Thread::withTrashed()
                ->where('id', $id)
                ->first();


            $restore_thread->restore();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }
}

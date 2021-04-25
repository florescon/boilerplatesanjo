<?php

namespace App\Http\Livewire\Backend\Line;

use App\Models\Line;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LineTable extends TableComponent
{

    use HtmlComponents;

    use WithPagination;


    public $search;

    public $status;

    public $perPage = '10';

    public $tableFooterEnabled = true;

    public $perPageOptions = ['10', '25', '50', '100'];

    public $exports = ['csv', 'xls', 'xlsx'];
    public $exportFileName = 'lines';

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

        $query = Line::query();

        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        return $query;
		// return Line::query()
        // ->when($this->deleted, function ($query) {
            // $query->onlyTrashed();
        // });
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
                ->format(function(Line $model) {
                    return $this->html($model->slug ? $model->slug : '<span class="badge badge-secondary">'.__('undefined').'</span>');
                })
                ->exportFormat(function(Line $model) {
                    return $model->slug;
                }),
            Column::make(__('Created at'), 'created_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Updated at'), 'updated_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Actions'))
                ->format(function (Line $model) {
                    return view('backend.line.datatable.actions', ['line' => $model]);
                }),
        ];
    }

    public function delete($id)
    {

        if($id){
            $line = Line::where('id', $id);
            $line->delete();

        }

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);

    }

    public function restore($id)
    {
        if($id){
            $restore_line = Line::withTrashed()
                ->where('id', $id)
                ->restore();
        }

      $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);

    }



}

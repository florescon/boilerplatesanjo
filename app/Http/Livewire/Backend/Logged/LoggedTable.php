<?php

namespace App\Http\Livewire\Backend\Logged;

use App\Models\Logged;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;
use geoip;

class LoggedTable extends TableComponent
{
    use HtmlComponents, WithPagination;

    public $search;

    public $perPage = '10';

    public $tableFooterEnabled = true;

    public $perPageOptions = ['10', '25', '50'];

    public $exports = ['csv', 'xls', 'xlsx'];
    public $exportFileName = 'logged-in';

    public $clearSearchButton = true;
 
    public $edateInput = '';
    public $edateOutput = '';
   
    protected $queryString = [
        'search' => ['except' => ''], 
        'perPage',
        'edateInput' => ['except' => ''],
        'edateOutput' => ['except' => '']
    ];

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
        'bootstrap.classes.thead' => 'thead-dark border-bottom-3px-yellow',
        'bootstrap.responsive' => true,
    ];

    protected $listeners = ['edateInput' => 'edateInput', 'edateOutput' => 'edateOutput', 'eclearFilterDate' => 'eclearFilterDate'];    

    public function edateInput($dateInput)
    {
        $this->edateInput = $dateInput;
    }

    public function edateOutput($dateOutput)
    {
        $this->edateOutput = $dateOutput;
    }

    public function eclearFilterDate()
    {
        $this->edateInput = '';
        $this->edateOutput = '';
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = Logged::query()->with('user')
            ->when($this->edateInput, function ($query) {
                empty($this->edateOutput) ?
                    $query->whereBetween('created_at', [$this->edateInput.' 00:00:00', now()]) :
                    $query->whereBetween('created_at', [$this->edateInput.' 00:00:00', $this->edateOutput.' 23:59:59']);
            })
            ->when(!$this->edateInput, function ($query) {
                $query->whereYear('created_at', now()->year);
            });

        return $query;
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('User'), 'user.name')
                ->searchable()
                ->sortable(),
            Column::make(__('Email'), 'user.email')
                ->searchable()
                ->sortable(),
            Column::make(__('Login At'), 'last_login_at')
                ->searchable()
                ->sortable(),
            Column::make(__('IP Address'), 'last_login_ip')
                ->searchable(),
            Column::make(__('Location'))
                ->format(function (Logged $model) {
                    return view('backend.logged.datatable.details', ['logged' => $model]);
                })
                ->excludeFromExport(),
        ];
    }
}
<?php

namespace App\Http\Livewire\Backend\Vendor;

use App\Models\Vendor;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use App\Events\Vendor\VendorDeleted;
// use App\Events\Vendor\VendorRestored;

class VendorTable extends TableComponent
{
    use HtmlComponents, WithPagination;

    public $search;

    public $status;

    public $perPage = '10';

    public $tableFooterEnabled = true;

    public $perPageOptions = ['10', '25', '50', '100'];

    public $exports = ['csv', 'xls', 'xlsx'];
    public $exportFileName = 'vendors';

    public $clearSearchButton = true;
    
    protected $queryString = [
        'search' => ['except' => ''], 
        'perPage',
    ];

    protected $listeners = ['internal', 'extra', 'delete', 'restore', 'triggerRefresh' => '$refresh'];

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
        $query = Vendor::query()->with('city');

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
            Column::make(__('Short name'), 'short_name')
                ->searchable()
                ->sortable()
                ->format(function(Vendor $model) {
                    return $this->html($model->short_name_label);
                })
                ->exportFormat(function(Vendor $model) {
                    return $model->short_name;
                }),
            Column::make(__('Email'), 'email')
                ->searchable()
                ->sortable()
                ->format(function(Vendor $model) {
                    return $this->html($model->email ?: '<span class="badge badge-secondary">'.__('undefined').'</span>');
                })
                ->exportFormat(function(Vendor $model) {
                    return $model->email;
                }),
            Column::make(__('Phone'), 'phone')
                ->searchable()
                ->sortable()
                ->format(function(Vendor $model) {
                    return $this->html($model->phone ?: '<span class="badge badge-secondary">'.__('undefined').'</span>');
                })
                ->exportFormat(function(Vendor $model) {
                    return $model->phone;
                }),
            Column::make(__('Location'), 'city.city')
                ->searchable()
                ->format(function(Vendor $model) {
                    return $model->city_id ? (optional($model->city)->city.' '.optional($model->city)->capital.' '.optional($model->city)->country) : '';
                }),
            Column::make(__('Address'), 'address')
                ->searchable()
                ->exportOnly(),
            Column::make(__('RFC'), 'rfc')
                ->searchable()
                ->exportOnly(),
            Column::make(__('Comment'), 'comment')
                ->searchable()
                ->exportOnly(),
            Column::make('# '.__('Associated products'), 'count_products')
                ->format(function(Vendor $model) {
                    return $this->link(route('admin.vendor.associates', $model->id), $model->count_products);
                }),
            Column::make('# '.__('Associated materials'), 'count_materials')
                ->format(function(Vendor $model) {
                    return $this->link(route('admin.vendor.associates_materia', $model->id), $model->count_materials.' — '.$model->total_percentage_materia.'%');
                }),
            Column::make(__('Internal vendor'), 'is_internal')
                ->format(function (Vendor $model) {
                    return view('backend.vendor.datatable.internal', ['vendor' => $model]);
                })
                ->excludeFromExport(),
            Column::make(__('Updated at'), 'updated_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Actions'))
                ->format(function (Vendor $model) {
                    return view('backend.vendor.datatable.actions', ['vendor' => $model]);
                })
                ->excludeFromExport(),
        ];
    }

    public function internal(?int $id = null)
    {
        if($id){
            $vendor = Vendor::withTrashed()->find($id);
            
            $vendor->update([
                'is_internal' => $vendor->is_internal ? false : true,
            ]);

            sleep(1);
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Changed'), 
        ]);
    }

    public function delete(Vendor $vendor)
    {

        // event(new VendorDeleted($vendor));

        $vendor->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function restore(?int $id = null)
    {
        if($id){
            $restore_vendor = Vendor::withTrashed()->find($id);

            // event(new VendorRestored($restore_vendor));

            $restore_vendor->restore();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }
}

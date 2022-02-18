<?php

namespace App\Http\Livewire\Backend\Gallery;

use Livewire\Component;
use App\Models\Imgage;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;

class GalleryTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    public $perPage = '4';

    public $status;
    public $searchTerm = '';

    public function getRowsQueryProperty()
    {
        $query = Imgage::query()
            ->whereType('3')
            ->orderBy('updated_at', 'desc');

        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        $this->applySearchFilter($query);

        return $query;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    private function applySearchFilter($products)
    {
        if ($this->searchTerm) {
            return $products->whereRaw("title LIKE \"%$this->searchTerm%\"")
                            ->orWhereRaw("sort LIKE \"%$this->searchTerm%\"");
        }

        return null;
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '12';
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function restore($id)
    {
        if($id){
            Image::withTrashed()->find($id)->restore();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }

    public function activateImage($modelId)
    {
        Image::whereId($modelId)->update(['is_active' => true]);
        $this->redirectPages();
    }

    public function desactivateImage($modelId)
    {
        Image::whereId($modelId)->update(['is_active' => false]);
        $this->redirectPages();
    }

    private function redirectPages()
    {
        return $this->redirectRoute('admin.setting.banner');
    }

    public function changeActive($modelId)
    {
        $data = Image::find($modelId);
        if($data->isActive()){
            $this->desactivateProduct($modelId);
        }
        else{
            $this->activateProduct($modelId);
        }
    }

    public function render()
    {
        return view('backend.setting.livewire.banner-table');
    }
}

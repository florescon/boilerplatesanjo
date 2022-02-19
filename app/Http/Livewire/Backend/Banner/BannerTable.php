<?php

namespace App\Http\Livewire\Backend\Banner;

use Livewire\Component;
use App\Models\Image;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Livewire\WithFileUploads;

class BannerTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    public $perPage = '20';

    public $status;
    public $searchTerm = '';

    public $files = [];

    protected $listeners = [
        'forceRender' => 'render'
    ];

    public function getRowsQueryProperty()
    {
        $query = Image::query()
            ->whereType('1')
            ->orderBy('sort');

        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        return $query;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
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
        $this->redirectHere();
    }

    public function desactivateImage($modelId)
    {
        Image::whereId($modelId)->update(['is_active' => false]);
        $this->redirectHere();
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

    public function savePictures()
    {
        $date = date("Y-m-d");

        $allImages = Image::whereType('1')->count();

        if($this->files){
            foreach($this->files as $phot){
                if($allImages >= 8){
                    break;
                }
                else{
                    $imageName = $phot->store("images/".$date,'public');
                    $image = new Image;
                    $image->image = $imageName;
                    $image->type = 1;
                    $image->save();
    
                    $allImages++;
                }
            }
        }
        
        // $this->init();

        $this->redirectHere();
    }

    public function redirectHere()
    {
        return redirect()->route('admin.setting.banner');
    }

    public function removeFromPicture(int $imageId): void
    {
        $picProduct = Image::find($imageId);
        $picProduct->delete(); 

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);

        $this->emit('forceRender');
    }

    public function render()
    {
        return view('backend.setting.livewire.banner-table', [
            'logos' => $this->rows,
        ]);
    }
}

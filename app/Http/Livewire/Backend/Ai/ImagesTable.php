<?php

namespace App\Http\Livewire\Backend\Ai;

use Livewire\Component;
use App\Models\Image;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Livewire\WithFileUploads;

class ImagesTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    public $perPage = '9';

    public $status, $deleted;
    public $searchTerm = '';

    public $files = [];

    public ?int $countImages = 50;

    protected $listeners = [
        'forceRender' => 'render'
    ];

    protected $rules = [
        'files.*' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
    ];

    protected $messages = [
        'files.*.mimes' => 'Tipo de archivo no permitido.',
        'files.*.image' => 'Debe ser imagen.',
    ];

    public function getRowsQueryProperty()
    {
        $query = Image::query()
            ->whereType('4')
            ->when($this->searchTerm, function ($query) {
                $query->where('title', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('created_at', 'desc');

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
        $this->validate();

        $date = date("Y-m-d");

        $allImages = Image::whereType('4')->count();

        if($this->files){
            foreach($this->files as $phot){
                if($allImages >= $this->countImages){
                    break;
                }
                else{
                    $imageName = $phot->store("images/".$date,'public');
                    $image = new Image;
                    $image->image = $imageName;
                    $image->type = 4;
                    $image->save();
    
                    $allImages++;
                }
            }
        }

        $this->redirectHere();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '9';
    }

    public function redirectHere()
    {
        return redirect()->route('admin.setting.images_ai');
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
        return view('backend.setting.livewire.images-table', [
            'logos' => $this->rows,
        ]);
    }
}

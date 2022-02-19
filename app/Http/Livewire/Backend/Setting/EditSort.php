<?php

namespace App\Http\Livewire\Backend\Setting;

use Livewire\Component;
use App\Models\Image;

class EditSort extends Component
{
    public $imageId;
    public $origSort; // initial image sort state
    public $sort; // dirty image sort state
    public $isSort; // determines whether to display it in bold text
    public string $extraName;

    protected $rules = [
        'sort' => 'required|integer|between:1,100',
    ];

    public function mount(Image $image, string $extraName)
    {
        $this->imageId = $image->id;
        $this->origSort = $image->sort;
        $this->extraName = $extraName;

        $this->init($image); // initialize the component state
    }

    public function save()
    {
        $this->validate();

        $image = Image::findOrFail($this->imageId);
        $image->sort = $this->sort ?? null;
        $image->save();

        $this->init($image); // re-initialize the component state with fresh data after saving

        $this->emit('forceRender');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    private function init(Image $image)
    {
        $this->origSort = $image->sort;
        $this->sort = $this->origSort;
        $this->isSort = $image->sort ?? false;
    }

    public function render()
    {
        return view('backend.setting.livewire.edit-sort');
    }
}
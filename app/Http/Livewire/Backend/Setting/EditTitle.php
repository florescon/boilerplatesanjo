<?php

namespace App\Http\Livewire\Backend\Setting;

use Livewire\Component;
use App\Models\Image;

class EditTitle extends Component
{
    public $imageId;
    public $origTitle; // initial image title state
    public $title; // dirty image title state
    public $isTitle; // determines whether to display it in bold text
    public string $extraName;

    protected $rules = [
        'title' => 'required|max:24|regex:/^\S*$/u',
    ];

    public function mount(Image $image, string $extraName)
    {
        $this->imageId = $image->id;
        $this->origTitle = $image->title;
        $this->extraName = $extraName;

        $this->init($image); // initialize the component state
    }

    public function save()
    {
        $this->validate();

        $image = Image::findOrFail($this->imageId);
        $image->title = $this->title ?? null;
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
        $this->origTitle = $image->title;
        $this->title = $this->origTitle;
        $this->isTitle = $image->title ?? false;
    }

    public function render()
    {
        return view('backend.setting.livewire.edit-title');
    }
}

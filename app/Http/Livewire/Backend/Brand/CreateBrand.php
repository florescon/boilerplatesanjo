<?php

namespace App\Http\Livewire\Backend\Brand;

use App\Models\Brand;
use Livewire\Component;

class CreateBrand extends Component
{
    public $name, $website;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:1|max:30',
        'website' => 'sometimes|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'
    ];

    private function resetInputFields()
    {
        $this->resetValidation();
        $this->name = '';
        $this->website = '';
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $validatedData = $this->validate();

        Brand::create($validatedData);

        $this->resetInputFields();
        $this->emit('brandStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);

        $this->emitTo('backend.brand.brand-table', 'triggerRefresh');
    }

    public function render()
    {
        return view('backend.brand.create-brand');
    }
}
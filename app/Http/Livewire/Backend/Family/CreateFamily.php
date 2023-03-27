<?php

namespace App\Http\Livewire\Backend\Family;

use App\Models\Family;
use Livewire\Component;

class CreateFamily extends Component
{
    public $name;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:1|max:30',
    ];

    private function resetInputFields()
    {
        $this->resetValidation();
        $this->name = '';
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

        $family = Family::create($validatedData);

        $this->resetInputFields();
        $this->emit('familyStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);

        $this->emitTo('backend.family.family-table', 'triggerRefresh');
    }

    public function render()
    {
        return view('backend.family.create-family');
    }
}

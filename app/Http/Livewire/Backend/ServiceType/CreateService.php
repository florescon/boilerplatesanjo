<?php

namespace App\Http\Livewire\Backend\ServiceType;

use App\Models\ServiceType;
use Livewire\Component;

class CreateService extends Component
{
    public $name;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:1|max:20',
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

        $service = ServiceType::create($validatedData);

        $this->resetInputFields();
        $this->emit('serviceTypeStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);

        $this->emitTo('backend.service-type.service-table', 'triggerRefresh');
    }

    public function render()
    {
        return view('backend.servicetype.create-service');
    }
}

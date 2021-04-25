<?php

namespace App\Http\Livewire\Backend\Unit;

use App\Models\Unit;
use Livewire\Component;

class CreateUnit extends Component
{
    public $name;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:3',
    ];

    private function resetInputFields()
    {
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

        Unit::create($validatedData);

        $this->resetInputFields();
        $this->emit('unitStore');


		$this->emit('swal:alert', [
		    'icon' => 'success',
		    'title'   => __('Created'), 
		]);


    	$this->emitTo('backend.unit.unit-table', 'triggerRefresh');


    }

    public function render()
    {
        return view('backend.unit.create-unit');
    }
}

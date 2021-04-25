<?php

namespace App\Http\Livewire\Backend\Size;

use App\Models\Size;
use Livewire\Component;

class CreateSize extends Component
{

    public $name;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:1',
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

        Size::create($validatedData);

        $this->resetInputFields();
        $this->emit('sizeStore');


		$this->emit('swal:alert', [
		    'icon' => 'success',
		    'title'   => __('Created'), 
		]);


    	$this->emitTo('backend.size.size-table', 'triggerRefresh');


    }

    public function render()
    {
        return view('backend.size.create-size');
    }

}

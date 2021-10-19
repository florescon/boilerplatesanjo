<?php

namespace App\Http\Livewire\Backend\Size;

use App\Models\Size;
use Livewire\Component;

class CreateSize extends Component
{

    public $name, $short_name;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:3',
        'short_name' => 'required|min:3|max:6|unique:sizes',
    ];

    private function resetInputFields()
    {
        $this->resetValidation();
        $this->name = '';
        $this->short_name = '';

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

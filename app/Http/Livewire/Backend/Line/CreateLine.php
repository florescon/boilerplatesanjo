<?php

namespace App\Http\Livewire\Backend\Line;

use App\Models\Line;
use Livewire\Component;

class CreateLine extends Component
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

        Line::create($validatedData);

        $this->resetInputFields();
        $this->emit('lineStore');


		$this->emit('swal:alert', [
		    'icon' => 'success',
		    'title'   => __('Created'), 
		]);


    	$this->emitTo('backend.line.line-table', 'triggerRefresh');


    }

    public function render()
    {
        return view('backend.line.create-line');
    }

}

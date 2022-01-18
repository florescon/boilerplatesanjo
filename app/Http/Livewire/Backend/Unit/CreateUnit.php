<?php

namespace App\Http\Livewire\Backend\Unit;

use App\Models\Unit;
use Livewire\Component;
use App\Events\Unit\UnitCreated;

class CreateUnit extends Component
{
    public $name, $abbreviation;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:3',
        'abbreviation' => 'required|min:1|max:4',
    ];

    private function resetInputFields()
    {
        $this->name = '';
        $this->abbreviation = '';
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

        $unit = Unit::create($validatedData);

        $this->resetInputFields();
        $this->emit('unitStore');

        event(new UnitCreated($unit));

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

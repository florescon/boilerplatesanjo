<?php

namespace App\Http\Livewire\Backend\Size;

use App\Models\Size;
use Livewire\Component;
use App\Events\Size\SizeCreated;

class CreateSize extends Component
{
    public $name, $short_name;

    public $sort = 0;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:1|max:20',
        'short_name' => 'required|min:1|max:4|regex:/^\S*$/u|unique:sizes',
        'sort' => 'sometimes|integer|min:0'
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

        $size = Size::create($validatedData);

        $this->resetInputFields();
        $this->emit('sizeStore');

        event(new SizeCreated($size));

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

<?php

namespace App\Http\Livewire\Backend\Size;

use App\Models\Size;
use Livewire\Component;

class CreateSize extends Component
{
    public $name, $short_name;
    public bool $is_parent_size = false;
    public ?int $parent = null;
    
    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:1',
        'short_name' => 'required_if:is_parent_size,false|nullable|min:1|max:6|unique:sizes',
    ];

    private function resetInputFields()
    {
        $this->resetValidation();
        $this->name = '';
        $this->short_name = '';
        $this->is_parent_size = false;
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

        Size::create([
            'name' => $this->name,
            'short_name' => !$this->is_parent_size ? $this->short_name : null,
            'parent_id' => $this->parent ?: null,
            'is_parent' => $this->is_parent_size ? true : false,
        ]);

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

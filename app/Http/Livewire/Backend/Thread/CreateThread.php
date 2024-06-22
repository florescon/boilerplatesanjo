<?php

namespace App\Http\Livewire\Backend\Thread;

use App\Models\Thread;
use Livewire\Component;

class CreateThread extends Component
{
    public $name;
    public $code;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:3|max:15',
        'code' => 'required|min:3|max:50',
    ];

    private function resetInputFields()
    {
        $this->name = '';
        $this->code = '';
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

        $thread = Thread::create($validatedData);

        $this->resetInputFields();
        $this->emit('threadStore');


		$this->emit('swal:alert', [
		    'icon' => 'success',
		    'title'   => __('Created'), 
		]);

    	$this->emitTo('backend.thread.thread-table', 'triggerRefresh');
    }

    public function render()
    {
        return view('backend.thread.create-thread');
    }
}

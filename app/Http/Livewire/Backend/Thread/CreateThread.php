<?php

namespace App\Http\Livewire\Backend\Thread;

use App\Models\Thread;
use App\Models\Vendor;
use Livewire\Component;

class CreateThread extends Component
{
    public $name;
    public $code;

    public $vendors;

    public $vendor_id;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:3|max:35',
        'code' => 'required|min:3|max:50|unique:threads',
        'vendor_id' => 'required|integer',
    ];

    public function mount()
    {
        $this->vendors = Vendor::all();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->code = '';
    }

    public function createmodal()
    {
        $this->resetInputFields();
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

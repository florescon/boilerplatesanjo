<?php

namespace App\Http\Livewire\Backend\Thread;

use App\Models\Thread;
use Livewire\Component;
use App\Models\Vendor;

class EditThread extends Component
{
    public $selected_id, $name, $code;

    public $vendor_id;
    
    protected $listeners = ['edit'];

    public function mount()
    {
        $this->vendors = Vendor::all();
    }

    public function edit($id)
    {
        $record = Thread::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->code = $record->code;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:3|max:15',
            'code' => 'required|min:3|max:50|unique:threads,code,'.$this->selected_id,
            'vendor_id' => 'required|integer',
        ]);
        if ($this->selected_id) {
            $thread = Thread::find($this->selected_id);
            $thread->update([
                'name' => $this->name,
                'code' => $this->code,
                'vendor_id' => $this->vendor_id,
            ]);
            // $this->resetInputFields();
        }


        $this->emit('threadUpdate');
        $this->emitTo('backend.thread.thread-table', 'triggerRefresh');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);
    }

    public function render()
    {
        return view('backend.thread.edit-thread');
    }
}
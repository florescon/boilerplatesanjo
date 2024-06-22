<?php

namespace App\Http\Livewire\Backend\Thread;

use App\Models\Thread;
use Livewire\Component;

class EditThread extends Component
{
    public $selected_id, $name, $code;

    protected $listeners = ['edit'];

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
            'code' => 'required|min:3|max:50',
        ]);
        if ($this->selected_id) {
            $thread = Thread::find($this->selected_id);
            $thread->update([
                'name' => $this->name,
                'code' => $this->code,
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
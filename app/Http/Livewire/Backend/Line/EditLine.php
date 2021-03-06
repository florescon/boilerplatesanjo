<?php

namespace App\Http\Livewire\Backend\Line;

use App\Models\Line;
use Livewire\Component;
use App\Events\Line\LineUpdated;

class EditLine extends Component
{
    public $selected_id, $name, $slug;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $record = Line::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->slug = $record->slug;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:3|max:15',
        ]);
        if ($this->selected_id) {
            $line = Line::find($this->selected_id);
            $line->update([
                'name' => $this->name,
            ]);
            // $this->resetInputFields();
        }

        event(new LineUpdated($line));

        $this->emit('lineUpdate');
        $this->emitTo('backend.line.line-table', 'triggerRefresh');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);
    }

    public function render()
    {
        return view('backend.line.edit-line');
    }
}
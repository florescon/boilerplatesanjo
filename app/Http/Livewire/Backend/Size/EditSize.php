<?php

namespace App\Http\Livewire\Backend\Size;

use App\Models\Size;
use Livewire\Component;

class EditSize extends Component
{

    public $selected_id, $name, $slug;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $record = Size::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->slug = $record->slug;

    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:3',
        ]);
        if ($this->selected_id) {
            $record = Size::find($this->selected_id);
            $record->update([
                'name' => $this->name,
            ]);
            // $this->resetInputFields();
        }

        $this->emit('sizeUpdate');
        $this->emitTo('backend.size.size-table', 'triggerRefresh');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);

    }


    public function render()
    {
        return view('backend.size.edit-size');
    }

}

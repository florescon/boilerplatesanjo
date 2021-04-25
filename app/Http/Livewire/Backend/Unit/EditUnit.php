<?php

namespace App\Http\Livewire\Backend\Unit;

use App\Models\Unit;
use Livewire\Component;

class EditUnit extends Component
{

    public $selected_id, $name, $slug;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $record = Unit::withTrashed()->findOrFail($id);
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
            $record = Unit::find($this->selected_id);
            $record->update([
                'name' => $this->name,
            ]);
            // $this->resetInputFields();
        }

        $this->emit('unitUpdate');
        $this->emitTo('backend.unit.unit-table', 'triggerRefresh');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);

    }


    public function render()
    {
        return view('backend.unit.edit-unit');
    }

}

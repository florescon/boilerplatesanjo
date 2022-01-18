<?php

namespace App\Http\Livewire\Backend\Unit;

use App\Models\Unit;
use Livewire\Component;
use App\Events\Unit\UnitUpdated;

class EditUnit extends Component
{
    public $selected_id, $name, $abbreviation, $slug;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $record = Unit::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->abbreviation = $record->abbreviation;
        $this->slug = $record->slug;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:3',
            'abbreviation' => 'required|min:1|max:4',
        ]);
        if ($this->selected_id) {
            $unit = Unit::find($this->selected_id);
            $unit->update([
                'name' => $this->name,
                'abbreviation' => $this->abbreviation,
            ]);
            // $this->resetInputFields();
        }

        event(new UnitUpdated($unit));

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

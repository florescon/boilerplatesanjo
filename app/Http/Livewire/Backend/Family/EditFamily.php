<?php

namespace App\Http\Livewire\Backend\Family;

use App\Models\Family;
use Livewire\Component;

class EditFamily extends Component
{
    public $selected_id, $name, $slug;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $this->resetInputFields();

        $record = Family::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->slug = $record->slug;
    }

    private function resetInputFields()
    {
        $this->resetValidation();
        $this->name = '';
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:1|max:30',
        ]);
        if ($this->selected_id) {
            $family = Family::find($this->selected_id);
            $family->update([
                'name' => $this->name,
            ]);
        }

        $this->emit('familyUpdate');
        $this->emitTo('backend.family.family-table', 'triggerRefresh');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);
    }

    public function render()
    {
        return view('backend.family.edit-family');
    }
}

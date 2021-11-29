<?php

namespace App\Http\Livewire\Backend\Size;

use App\Models\Size;
use Livewire\Component;

class EditSize extends Component
{

    public $selected_id, $name, $short_name, $slug;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $this->resetInputFields();

        $record = Size::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->short_name = $record->short_name;
        $this->slug = $record->slug;
    }

    private function resetInputFields()
    {
        $this->resetValidation();
        $this->name = '';
        $this->short_name = '';
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:1',
            'short_name' => 'required|min:1|max:6|unique:App\Models\Size,short_name,'.$this->selected_id,
        ]);
        if ($this->selected_id) {
            $record = Size::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'short_name' => $this->short_name,
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

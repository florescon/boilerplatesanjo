<?php

namespace App\Http\Livewire\Backend\Cloth;

use App\Models\Cloth;
use Livewire\Component;
use App\Events\Cloth\ClothUpdated;

class EditCloth extends Component
{
    public $selected_id, $name, $slug;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $record = Cloth::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->slug = $record->slug;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:3|max:20',
        ]);
        if ($this->selected_id) {
            $cloth = Cloth::find($this->selected_id);
            $cloth->update([
                'name' => $this->name,
            ]);
            // $this->resetInputFields();
        }

        event(new ClothUpdated($cloth));

        $this->emit('clothUpdate');
        $this->emitTo('backend.cloth.cloth-table', 'triggerRefresh');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);
    }

    public function render()
    {
        return view('backend.cloth.edit-cloth');
    }
}

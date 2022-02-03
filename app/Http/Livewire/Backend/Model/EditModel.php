<?php

namespace App\Http\Livewire\Backend\Model;

use App\Models\ModelProduct;
use Livewire\Component;
use App\Events\ModelProduct\ModelProductUpdated;

class EditModel extends Component
{
    public $selected_id, $name, $slug;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $record = ModelProduct::withTrashed()->findOrFail($id);
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
            $model_product = ModelProduct::find($this->selected_id);
            $model_product->update([
                'name' => $this->name,
            ]);
            // $this->resetInputFields();
        }

        event(new ModelProductUpdated($model_product));

        $this->emit('modelUpdate');
        $this->emitTo('backend.model.model-table', 'triggerRefresh');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);
    }

    public function render()
    {
        return view('backend.model.edit-model');
    }
}

<?php

namespace App\Http\Livewire\Backend\ServiceOrder;

use App\Models\ServiceOrder;
use Livewire\Component;

class EditServiceOrder extends Component
{
    public $selected_id, $dimensions, $comment, $file_text;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $this->resetInputFields();

        $record = ServiceOrder::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->comment = $record->comment;
        $this->dimensions = $record->dimensions;
        $this->file_text = $record->file_text;
    }

    private function resetInputFields()
    {
        $this->resetValidation();
        $this->comment = '';
        $this->dimensions = '';
        $this->file_text = '';
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'comment' => 'nullable|min:1|max:255',
            'dimensions' => 'nullable|min:1|max:255',
            'file_text' => 'nullable|min:1|max:255',
        ]);
        if ($this->selected_id) {
            $record = ServiceOrder::find($this->selected_id);
            $record->update([
                'comment' => $this->comment,
                'dimensions' => $this->dimensions,
                'file_text' => $this->file_text,
            ]);
        }

        $this->emit('serviceOrderUpdate');
        $this->emit('triggerRefresh');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);
    }

    public function render()
    {
        return view('backend.serviceorder.edit-service-order');
    }
}

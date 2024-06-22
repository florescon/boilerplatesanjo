<?php

namespace App\Http\Livewire\Backend\ServiceType;

use App\Models\ServiceType;
use Livewire\Component;
use Illuminate\Validation\Rule;

class EditService extends Component
{
    public $selected_id, $name, $slug;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $this->resetInputFields();

        $record = ServiceType::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
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
            'name' => 'required|min:1|max:20',
        ]);
        if ($this->selected_id) {
            $service = ServiceType::find($this->selected_id);
            $service->update([
                'name' => $this->name,
            ]);
            // $this->resetInputFields();
        }

        $this->emit('serviceTypeUpdate');
        $this->emitTo('backend.service-type.service-table', 'triggerRefresh');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);
    }

    public function render()
    {
        return view('backend.servicetype.edit-service');
    }
}

<?php

namespace App\Http\Livewire\Backend\Brand;

use App\Models\Brand;
use Livewire\Component;

class EditBrand extends Component
{
    public $selected_id, $name, $website, $description, $slug;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $this->resetInputFields();

        $record = Brand::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->website = $record->website;
        $this->slug = $record->slug;
        $this->description = $record->description;
    }

    private function resetInputFields()
    {
        $this->resetValidation();
        $this->name = '';
        $this->website = '';
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:1|max:30',
            'website' => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'
        ]);
        if ($this->selected_id) {
            $record = Brand::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'website' => $this->website,
            ]);
            // $this->resetInputFields();
        }

        $this->emit('brandUpdate');
        $this->emitTo('backend.brand.brand-table', 'triggerRefresh');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);
    }

    public function render()
    {
        return view('backend.brand.edit-brand');
    }
}
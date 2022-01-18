<?php

namespace App\Http\Livewire\Backend\Model;

use App\Models\ModelProduct;
use Livewire\Component;
use App\Events\ModelProduct\ModelProductCreated;

class CreateModel extends Component
{
    public $name;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:3|max:15',
    ];

    private function resetInputFields()
    {
        $this->name = '';
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $validatedData = $this->validate();

        $model_product = ModelProduct::create($validatedData);

        $this->resetInputFields();
        $this->emit('modelStore');

        event(new ModelProductCreated($model_product));

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);

        $this->emitTo('backend.model.model-table', 'triggerRefresh');
    }

    public function render()
    {
        return view('backend.model.create-model');
    }
}

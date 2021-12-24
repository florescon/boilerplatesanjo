<?php

namespace App\Http\Livewire\Backend\Service;

use Livewire\Component;
use App\Models\Product;

class CreateService extends Component
{
    public $name, $price, $code;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:3',
        'code' => 'nullable|min:3|max:15|regex:/^\S*$/u|unique:products',
        'price' => 'required|numeric',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->code = '';
        $this->price = '';
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        try {
            $this->validate();

            Product::create([
                'name' => $this->name,                
                'code' => $this->code ?: null,                
                'price' => $this->price,                
                'type' => false,                
            ]);

            $this->resetInputFields();

            $this->emit('serviceStore');

            $this->emitTo('backend.service.service-table', 'triggerRefresh');

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Created'), 
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating the service.'));
        }
    }

    public function render()
    {
        return view('backend.service.create-service');
    }
}

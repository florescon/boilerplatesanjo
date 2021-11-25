<?php

namespace App\Http\Livewire\Backend\Store\Pos;

use App\Models\Cash;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateCash extends Component
{

    public ?string $initial = null;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'initial' => 'required|numeric|min:1',
    ];

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private function resetInputFields()
    {
        $this->initial = '';
    }

    public function store()
    {
        $validatedData = $this->validate();

        Cash::updateOrCreate(
            ['checked' => null],
            ['initial' => $this->initial],
        );
        
        $this->resetInputFields();
        $this->emit('cashStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated'), 
        ]);
    }

    public function render()
    {
        return view('backend.store.pos.create-cash');
    }
}

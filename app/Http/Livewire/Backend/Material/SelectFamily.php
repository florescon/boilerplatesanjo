<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;

class SelectFamily extends Component
{
    protected $listeners = ['postFamily' => 'getFamily'];

    public bool $mass = false;

    public ?int $family_id = null;

    public function getFamily($id){
        $this->family_id = $id;
        $this->emit('emitFamily', $this->family_id);
    }

    public function clear()
    {
        $this->family_id = null;
        $this->emit('emitFamily', null);
        $this->emit('clear-family');
    }

    public function render()
    {
        return view('backend.material.select-family');
    }
}

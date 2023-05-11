<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;

class SelectVendor extends Component
{
    protected $listeners = ['postVendor' => 'getVendor'];

    public bool $mass = false;

    public ?int $vendor_id = null;

    public function getVendor($id){
        $this->vendor_id = $id;
        $this->emit('emitVendor', $this->vendor_id);
    }

    public function clear()
    {
        $this->vendor_id = null;
        $this->emit('emitVendor', null);
        $this->emit('clear-vendor');
    }

    public function render()
    {
        return view('backend.material.select-vendor');
    }
}

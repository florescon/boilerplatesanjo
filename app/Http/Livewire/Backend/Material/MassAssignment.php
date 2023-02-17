<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;

class MassAssignment extends Component
{
    protected $listeners = ['postVendor' => 'getVendor'];

    public bool $mass = false;

    public ?int $vendor_id = null;

    public function getVendor($id){
        $this->vendor_id = $id;
    }

    public function render()
    {
        return view('backend.material.mass-assignment');
    }
}

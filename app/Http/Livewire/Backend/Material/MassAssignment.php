<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;

class MassAssignment extends Component
{
    protected $listeners = ['postVendor' => 'getVendor'];

    public bool $mass = false;

    public ?int $family_id = null;

    public function getVendor($id){
        $this->family_id = $id;
    }

    public function render()
    {
        return view('backend.material.mass-assignment');
    }
}
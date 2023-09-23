<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;

class SelectFamilySecond extends Component
{
    public $family_second_id;

    public bool $clear = false;

    public function clearSecondFamily()
    {
        $this->emit('clear-second-family');
    }

    public function render()
    {
        return view('backend.material.select-family-second');
    }
}

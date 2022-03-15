<?php

namespace App\Http\Livewire\Backend\Departament;

use Livewire\Component;

class SelectDepartamentDropdown extends Component
{
    public $user_id;

    public bool $clear = false;

    public function render()
    {
        return view('backend.departament.livewire.select-departaments-dropdown');
    }
}

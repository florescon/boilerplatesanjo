<?php

namespace App\Http\Livewire\Backend\Departament;

use Livewire\Component;

class SelectDepartaments extends Component
{
    public $user_id;

    public function render()
    {
        return view('backend.departament.livewire.select-departaments');
    }
}

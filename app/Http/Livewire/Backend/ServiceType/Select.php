<?php

namespace App\Http\Livewire\Backend\ServiceType;

use Livewire\Component;

class Select extends Component
{
    public $service_type_id;

    public ?bool $clear = false;

    public function render()
    {
        return view('backend.servicetype.livewire.select');
    }
}

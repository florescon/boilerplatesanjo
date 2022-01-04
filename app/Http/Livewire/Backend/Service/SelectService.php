<?php

namespace App\Http\Livewire\Backend\Service;

use Livewire\Component;

class SelectService extends Component
{
    public $service_id;

    public bool $clear = false;

    public function render()
    {
        return view('backend.service.livewire.select-service');
    }
}

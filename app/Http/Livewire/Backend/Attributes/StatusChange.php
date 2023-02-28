<?php

namespace App\Http\Livewire\Backend\Attributes;

use Livewire\Component;

class StatusChange extends Component
{
    public $status_id;

    public function render()
    {
        return view('backend.attributes.status-change');
    }
}

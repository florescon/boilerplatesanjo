<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;

class SelectVendorSecond extends Component
{
    public $vendor_second_id;

    public bool $clear = false;

    public function clearSecondVendor()
    {
        $this->emit('clear-second-vendor');
    }

    public function render()
    {
        return view('backend.material.select-vendor-second');
    }
}

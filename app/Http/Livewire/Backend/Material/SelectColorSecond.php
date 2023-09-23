<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;

class SelectColorSecond extends Component
{
    public $color_second_id;

    public bool $clear = false;

    public function clearSecondColor()
    {
        $this->emit('clear-second-color');
    }

    public function render()
    {
        return view('backend.material.select-color-second');
    }
}

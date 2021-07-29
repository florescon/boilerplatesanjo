<?php

namespace App\Http\Livewire\Frontend\Attributes;

use Livewire\Component;

class ColorChange extends Component
{
    public $color_id;

    public function render()
    {
        return view('frontend.attributes.color-change');
    }
}

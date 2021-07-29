<?php

namespace App\Http\Livewire\Frontend\Attributes;

use Livewire\Component;

class SizeChange extends Component
{
    public $size_id;

    public function render()
    {
        return view('frontend.attributes.size-change');
    }
}

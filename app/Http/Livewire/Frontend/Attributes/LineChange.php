<?php

namespace App\Http\Livewire\Frontend\Attributes;

use Livewire\Component;

class LineChange extends Component
{
    public $line_id;

    public function render()
    {
        return view('frontend.attributes.line-change');
    }
}

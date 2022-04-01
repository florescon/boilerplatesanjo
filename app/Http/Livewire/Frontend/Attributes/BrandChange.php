<?php

namespace App\Http\Livewire\Frontend\Attributes;

use Livewire\Component;

class BrandChange extends Component
{
    public $brand_id;

    public function render()
    {
        return view('frontend.attributes.brand-change');
    }
}

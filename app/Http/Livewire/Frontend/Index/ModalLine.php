<?php

namespace App\Http\Livewire\Frontend\Index;

use Livewire\Component;
use App\Models\Frontend\Line;
use App\Models\Frontend\Brand;

class ModalLine extends Component
{
    public function render()
    {
        $brands = Brand::withCount('products')->orderBy('name', 'asc')->get();

        return view('frontend.includes_ga.modal-filters')->with(compact('brands'));
    }
}

<?php

namespace App\Http\Livewire\Frontend\Index;

use Livewire\Component;
use App\Models\Frontend\Line;

class ModalLine extends Component
{

    public function render()
    {

        $lines = Line::withCount('products')->orderBy('name', 'asc')->get();
        return view('frontend.includes_ga.modal-filters')->with(compact('lines'));
    }

}

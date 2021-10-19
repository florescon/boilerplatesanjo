<?php

namespace App\Http\Livewire\Backend\Store\Pos;

use Livewire\Component;

class DetailsProduct extends Component
{

    public $name;

    protected $listeners = ['detailsproduct'];


    public function render()
    {
        return view('backend.store.pos.details-product');
    }

}

<?php

namespace App\Http\Livewire\Backend\Cloth;

use Livewire\Component;

class BodyCloth extends Component
{

	public $deleted;


	protected $queryString = [
        'deleted',
    ];

    protected $listeners = ['deleted'];


    public function render()
    {
        return view('backend.cloth.body-cloth');
    }
}

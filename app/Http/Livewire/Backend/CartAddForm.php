<?php

namespace App\Http\Livewire\Backend;

use Livewire\Component;

class CartAddForm extends Component
{
	public $product_id = [];

    public function store()
    {
	
	}

    public function render()
    {
        return view('backend.cart.livewire.cart-add-form');
    }
}

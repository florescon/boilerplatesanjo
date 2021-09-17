<?php

namespace App\Http\Livewire\Backend\Cart;

use Livewire\Component;

class UserCart extends Component
{
    public $user_id;

    public function render()
    {
        return view('backend.cart.livewire.user-cart');
    }
}

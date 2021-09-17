<?php

namespace App\Http\Livewire\Backend\User;

use Livewire\Component;

class OnlyUsers extends Component
{
    public $user_id;

    public function render()
    {
        return view('backend.user.livewire.only-users');
    }
}

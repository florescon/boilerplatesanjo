<?php

namespace App\Http\Livewire\Backend\User;

use Livewire\Component;

class OnlyAdmins extends Component
{
    public $user_id;

    public ?bool $clear = false;

    public function render()
    {
        return view('backend.user.livewire.only-admins');
    }
}

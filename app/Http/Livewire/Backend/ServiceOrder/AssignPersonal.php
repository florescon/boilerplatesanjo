<?php

namespace App\Http\Livewire\Backend\ServiceOrder;

use Livewire\Component;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceOrder;

class AssignPersonal extends Component
{
    public $selected_id;

    public $query;

    protected $listeners = ['assignpersonal'];

    public function assignpersonal($id)
    {
        $this->selected_id = $id;
    }

    public function reset_search()
    {
        $this->query = '';
    }

    public function selectUser($userId)
    {
        $serviceOrder = ServiceOrder::find($this->selected_id);
        $serviceOrder->update([
            'user_id' => $userId,
            'authorized_id' => Auth::id(),
        ]);

        $this->emit('triggerRefresh');

        $this->emit('assignPersonalUpdate');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated'), 
        ]);

    }

    public function updatedQuery()
    {
        $this->users = User::with('customer')->admins()
            ->whereRaw("name LIKE \"%$this->query%\"")
            ->get()->take(5)
            ->toArray();
    }

    public function render()
    {
        return view('backend.serviceorder.assign-personal');
    }
}

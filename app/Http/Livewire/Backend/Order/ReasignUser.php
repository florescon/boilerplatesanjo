<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Domains\Auth\Models\User;
use App\Models\Order;

class ReasignUser extends Component
{
    public $user;

    public $orderId;

    protected $listeners = ['createmodal', 'selectedCompanyItem'];

    protected $rules = [
        'user' => 'required',
    ];

    public function selectedCompanyItem($user)
    {
        // dd($this->parameter);
        if ($user){
            $this->user = $user;
        }
        else{
            $this->user = null;
        }
    }

    private function resetInputFields()
    {
        $this->user = '';
    }

    public function createmodal(int $id)
    {
        $this->orderId = $id;
        $this->resetInputFields();
    }

    public function store()
    {
        if($this->user){
            $order = Order::find($this->orderId);

            $order->update([
                'user_id' => null,
                'departament_id' => null,
            ]);

            $order->update([
                'user_id' => $this->user,
                'user_departament_changed_at' => now(),
            ]);

            $order->materials_order()->delete();
        }

        $this->emit('reasignUserStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated'), 
        ]);
    }

    public function render()
    {
        return view('backend.order.livewire.reasign-user');
    }
}

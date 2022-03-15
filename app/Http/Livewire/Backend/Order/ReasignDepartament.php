<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Departament;
use App\Models\Order;

class ReasignDepartament extends Component
{
    public $departament;

    public $orderId;

    protected $listeners = ['departamentmodal', 'selectedDeparament'];

    protected $rules = [
        'departament' => 'required',
    ];

    public function selectedDeparament($departament)
    {
        // dd($this->parameter);
        if ($departament){
            $this->departament = $departament;
        }
        else{
            $this->departament = null;
        }
    }

    private function resetInputFields()
    {
        $this->departament = '';
    }

    public function departamentmodal(int $id)
    {
        $this->orderId = $id;
        $this->resetInputFields();
    }

    public function store()
    {
        if($this->departament){
            $order = Order::find($this->orderId);

            $order->update([
                'user_id' => null,
                'departament_id' => null,
            ]);

            $order->update([
                'departament_id' => $this->departament,
                'user_departament_changed_at' => now(),
            ]);

            $order->materials_order()->delete();
        }

        $this->emit('reasignDepartamentStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated'), 
        ]);
    }

    public function render()
    {
        return view('backend.order.livewire.reasign-departament');
    }
}

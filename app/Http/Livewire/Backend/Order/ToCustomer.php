<?php

namespace App\Http\Livewire\Backend\Order;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ToCustomer extends Component
{
    public Model $model;
    public string $field;
    public bool $hasStock;    

    public function mount()
    {
      $this->hasStock = (bool) $this->model->getAttribute($this->field);
    }
    
    public function updating($field, $value)
    {
      $this->model->setAttribute($this->field, $value)->save();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated'), 
        ]);
    }

    public function render()
    {
        return view('backend.order.to-customer');
    }
}

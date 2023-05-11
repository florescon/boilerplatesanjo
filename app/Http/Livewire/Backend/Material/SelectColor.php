<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;

class SelectColor extends Component
{
    protected $listeners = ['postColor' => 'getColor'];

    public bool $mass = false;

    public ?int $color_id = null;

    public function getColor($id){
        $this->color_id = $id;
        $this->emit('emitColor', $this->color_id);
    }

    public function clear()
    {
        $this->color_id = null;
        $this->emit('emitColor', null);
        $this->emit('clear-color');
    }

    public function render()
    {
        return view('backend.material.select-color');
    }

}

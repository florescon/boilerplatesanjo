<?php

namespace App\Http\Livewire\Backend\Store\Pos;

use Livewire\Component;

class SearchProduct extends Component
{
    public $name;

    protected $listeners = ['searchproduct'];

    protected $rules = [
        'name' => 'required|min:2',
    ];

    private function resetInputFields()
    {
        $this->name = '';
    }

    public function searchproduct()
    {
        $this->resetInputFields();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('backend.store.pos.search-product');
    }
}

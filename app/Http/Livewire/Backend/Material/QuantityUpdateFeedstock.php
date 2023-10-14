<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;
use DB;

class QuantityUpdateFeedstock extends Component
{
    public $item = [];
    public $quantity = 1;
    public $itemID;
    public $setModel;

    protected $rules = [
        'quantity' => 'integer|min:1',
    ];

    public function mount($item, ?string $setModel = 'cart_feedstocks')
    {
        $this->item = $item;
        $this->itemID = $item->id;
        $this->quantity = $item->quantity ?: 1;
        $this->setModel = $setModel;
    }

    public function update()
    {
        $this->validate();

        $product = DB::table($this->setModel)
              ->where('id', $this->itemID)
              ->update(['quantity' => $this->quantity ?: 1, 'updated_at' => now()]);

        $this->emit('cartUpdated', $this->itemID);
    }

    public function render()
    {
        return view('backend.material.livewire.quantity-update-feedstock');
    }
}

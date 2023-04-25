<?php

namespace App\Http\Livewire\Backend\Cartdb;

use Livewire\Component;
use DB;

class PriceUpdate extends Component
{
    public $item = [];
    public $price = 1;
    public $itemID;
    public string $typeCart;
    public $setModel;

    protected $rules = [
        'price' => 'min:1|digits_between:1,12|not_in:0',
    ];

    public function mount($item, string $typeCart, ?string $setModel = 'carts')
    {
        $this->item = $item;
        $this->itemID = $item->id;
        $this->type = $typeCart;
        $this->price = $item->price ?: 1;
        $this->setModel = $setModel;
    }

    public function update()
    {
        $this->validate();

        $product = DB::table($this->setModel)
              ->where('id', $this->itemID)
              ->update([
                'price' => $this->price ?: 1, 
                'price_without_tax' => priceWithoutIvaIncluded($this->price), 
                'updated_at' => now()
            ]);

        $this->emit('cartUpdated', $this->itemID);
    }

    public function render()
    {
        return view('backend.cartdb.price-update');
    }
}

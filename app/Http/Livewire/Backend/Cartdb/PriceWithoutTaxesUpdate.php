<?php

namespace App\Http\Livewire\Backend\Cartdb;

use Livewire\Component;
use DB;

class PriceWithoutTaxesUpdate extends Component
{
    public $item = [];
    public $price_without_tax = 1;
    public $itemID;
    public string $typeCart;
    public $setModel;

    protected $rules = [
        'price_without_tax' => 'min:1',
    ];

    public function mount($item, string $typeCart, ?string $setModel = 'carts')
    {
        $this->item = $item;
        $this->itemID = $item->id;
        $this->type = $typeCart;
        $this->price_without_tax = $item->price_without_tax ?: 1;
        $this->setModel = $setModel;
    }

    public function update()
    {
        $this->validate();

        $product = DB::table($this->setModel)
              ->where('id', $this->itemID)
              ->update([
                'price' => priceIncludeIva($this->price_without_tax) ?: 1, 
                'price_without_tax' => $this->price_without_tax ?: 1, 
                'updated_at' => now()
            ]);

        $this->emit('cartUpdated', $this->itemID);
    }

    public function render()
    {
        return view('backend.cartdb.price-without-taxes-update');
    }
}

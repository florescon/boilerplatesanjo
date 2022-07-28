<?php

namespace App\Http\Livewire\Backend;

use Livewire\Component;
use App\Facades\Cart as CartFacade;
use App\Models\Product;

class CartShowPrice extends Component
{
    public $product;
    public $product_id;
    public string $typeCart;

    public $price;
    public $priceWithIva;

    protected $listeners = ['cartUpdated' => 'init'];

    public function mount(Product $product, string $typeCart)
    {
        $this->product_id = $product->id;
        $this->product = $product;
        $this->typeCart = $typeCart;
        $this->price = CartFacade::priceReal($product, $typeCart);
        $this->priceWithIva = number_format($this->priceWithIva($this->price), 2);
    }

    public function priceWithIva($price)
    {
        return $this->price + ((setting('iva') / 100) * $this->price);
    }

    public function init()
    {
        $this->price = CartFacade::priceReal($this->product, $this->typeCart);
        $this->priceWithIva($this->price);
    }

    public function render()
    {
        return view('backend.cart.livewire.cart-show-price');
    }
}

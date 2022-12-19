<?php

namespace App\Http\Livewire\Frontend\Shop;

use Livewire\Component;
use App\Models\Product;
use App\Models\Line;
use App\Facades\Cart;

class ShopParametersComponent extends Component
{
    public $product_parent;
    public ?int $color_id = null;
    public ?int $size_id = null;
    public int $amount;

    public function mount(Product $product)
    {
        $this->product_parent = $product->id;
        $this->amount = 1;
    }

    public function add_cart()
    {
        $this->validate([
            'color_id' => 'required',
            'size_id' => 'required',
            'amount' => 'gt:0'
        ]);

        $color_ = $this->color_id;
        $size_ = $this->size_id;

        $subproduct = Product::where('parent_id', $this->product_parent)
            ->where(function ($query) use ($color_) {
                $query->where('color_id', $color_);
            })
            ->where(function ($query) use ($size_) {
                $query->where('size_id', $size_);
            })
            ->first();

        $sub = Product::with(array('parent' => function($query) {
            $query->select('id', 'type', 'slug', 'name', 'code', 'price', 'file_name');
        }))->get()
        ->find($subproduct->id);

        $sub->setAttribute('amount', $this->amount);

        Cart::add($sub, 'products');
        $this->emit('productAdded');


        $this->clearParameters();

        session()->flash('message', __('Product successfully added'));

        // $this->current_task->update(['note' => $this->note]);
        // $this->note_opened = FALSE;
    }

    public function clearParameters()
    {
        $this->color_id = null;
        $this->size_id = null;
    }

    public function setColor($color)
    {
        $this->color_id = $color;
    }

    public function setSize($size)
    {
        $this->size_id = $size;
    }

    public function render()
    {
        $model = Product::with('pictures')->findOrFail($this->product_parent);
        $attributes = Product::with('children')->findOrFail($this->product_parent);

        return view('frontend.shop.livewire.shop-parameters-component')->with(compact('model', 'attributes'));
    }
}

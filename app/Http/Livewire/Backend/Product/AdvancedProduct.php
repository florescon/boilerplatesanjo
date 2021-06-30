<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;

class AdvancedProduct extends Component
{

    public $product_id, $description, $information;

    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->description = optional($product->advanced)->description;
        $this->information = optional($product->advanced)->information;

    }

    public function storedescription()
    {

        $product = Product::findOrFail($this->product_id);

        $this->validate([
            'description' => 'required',
        ]);

        $product->advanced()->updateOrCreate(['product_id' => $this->product_id], [
            'description' => $this->description,
        ]);

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);

    }

    public function storeinformation()
    {

        $product = Product::findOrFail($this->product_id);

        $this->validate([
            'information' => 'required',
        ]);

        $product->advanced()->updateOrCreate(['product_id' => $this->product_id], [
            'information' => $this->information,
        ]);

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);

    }


    public function render()
    {

        $model = Product::with('advanced')->findOrFail($this->product_id);

        return view('backend.product.livewire.advanced')->with(compact('model'));
    }
}

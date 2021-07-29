<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;

class AdvancedProduct extends Component
{

    public $product_id, $description, $information, $standards, $dimensions, $extra;

    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->information = optional($product->advanced)->information;
        $this->standards = optional($product->advanced)->standards;
        $this->dimensions = optional($product->advanced)->dimensions;
        $this->extra = optional($product->advanced)->extra;
        $this->description = optional($product->advanced)->description;

    }

    public function storedescription()
    {

        $product = Product::findOrFail($this->product_id);

        $this->validate([
            'description' => 'required',
        ]);

        $product->advanced()->updateOrCreate(
            ['product_id' => $this->product_id], 
            ['description' => $this->description,]
        );

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


    public function storedimensions()
    {

        $product = Product::findOrFail($this->product_id);

        $this->validate([
            'dimensions' => 'required',
        ]);

        $product->advanced()->updateOrCreate(['product_id' => $this->product_id], [
            'dimensions' => $this->dimensions,
        ]);

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);

    }


    public function storeextra()
    {

        $product = Product::findOrFail($this->product_id);

        $this->validate([
            'extra' => 'required',
        ]);

        $product->advanced()->updateOrCreate(['product_id' => $this->product_id], [
            'extra' => $this->extra,
        ]);

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);

    }

    public function storestandards()
    {

        $product = Product::findOrFail($this->product_id);

        $this->validate([
            'standards' => 'required',
        ]);

        $product->advanced()->updateOrCreate(['product_id' => $this->product_id], [
            'standards' => $this->standards,
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

<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;

class AdvancedProduct extends Component
{
    public Product $product;

    public int $product_id;
    public ?string $information = null;
    public ?string $standards  = null;
    public ?string $dimensions  = null;
    public ?string $extra  = null;
    public ?string $description  = null;

    protected $listeners = [
        'advancedRender' => 'render'
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->product_id = $product->id;
        $this->information = optional($product->advanced)->information;
        $this->standards = optional($product->advanced)->standards;
        $this->dimensions = optional($product->advanced)->dimensions;
        $this->extra = optional($product->advanced)->extra;
        $this->description = optional($product->advanced)->description;

        $this->emit('advancedRender');
    }

    public function storedescription()
    {
        $this->validate([
            'description' => 'required|min:3',
        ]);

        $this->product->advanced()->updateOrCreate(
            ['product_id' => $this->product_id], 
            ['description' => $this->description,]
        );

        $this->emit('advancedRender');

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function storeinformation()
    {
        $this->validate([
            'information' => 'required|min:3',
        ]);

        $this->product->advanced()->updateOrCreate(['product_id' => $this->product_id], [
            'information' => $this->information,
        ]);

        $this->emit('advancedRender');

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function storedimensions()
    {
        $this->validate([
            'dimensions' => 'required|min:3',
        ]);

        $this->product->advanced()->updateOrCreate(['product_id' => $this->product_id], [
            'dimensions' => $this->dimensions,
        ]);

        $this->emit('advancedRender');

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function storeextra()
    {
        $this->validate([
            'extra' => 'required|min:3',
        ]);

        $this->product->advanced()->updateOrCreate(['product_id' => $this->product_id], [
            'extra' => $this->extra,
        ]);

        $this->emit('advancedRender');

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function storestandards()
    {
        $this->validate([
            'standards' => 'required|min:3',
        ]);

        $this->product->advanced()->updateOrCreate(['product_id' => $this->product_id], [
            'standards' => $this->standards,
        ]);

        $this->emit('advancedRender');

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function clearField(int $id, string $Field)
    {
        $this->product->advanced()->update([$Field => null]);

        $this->initField($this->product, $Field);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    private function initField(Product $product, string $Field)
    {
        $this->$Field = $product->$Field;
    }

    public function clearAll()
    {
        $this->product->advanced()->delete();

        return redirect()->route('admin.product.advanced', $this->product_id);
    }

    public function activateDescription()
    {
        $this->product->advanced()->update(['status' => true]);
        return $this->redirectRoute('admin.product.advanced', $this->product_id);
    }

    public function desactivateDescription()
    {
        $this->product->advanced()->update(['status' => false]);
        return $this->redirectRoute('admin.product.advanced', $this->product_id);
    }

    public function render()
    {
        $model = Product::with('advanced')->findOrFail($this->product_id);

        return view('backend.product.livewire.advanced')->with(compact('model'));
    }
}
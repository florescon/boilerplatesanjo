<?php

namespace App\Http\Livewire\Backend\Product;

use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Livewire\Component;

class DeleteAttributesProduct extends Component
{
    public int $productId;
    public $product;

    public $filters = [];
    public $filtersz = [];

    public function mount(Product $product)
    {
        $this->productId = $product->id;
        $this->product = $this->product;
    }

    protected $listeners = ['filterByColor' => 'filterByColor', 'filterBySize' => 'filterBySize'];

    public function filterByColor($color)
    {
        if (in_array($color, $this->filters)) {
            $ix = array_search($color, $this->filters);
            unset($this->filters[$ix]);
        } else {
            $this->filters[] = $color;

            array_shift($this->filtersz);

            if(count($this->filters) >= 2){
                array_shift($this->filters);
            };
        }
    }

    public function filterBySize($size)
    {
        if (in_array($size, $this->filtersz)) {
            $ix = array_search($size, $this->filtersz);
            unset($this->filtersz[$ix]);
        } else {
            $this->filtersz[] = $size;

            array_shift($this->filters);

            if(count($this->filtersz) >= 2){
                array_shift($this->filtersz);
            };
        }
    }

    public function deleteColor($color)
    {
        $deleteColor = $this->product->childrenOnlyColors()
            ->where('color_id', $color)
            ->delete();

        return $this->redirectRoute('admin.product.delete-attributes', $this->productId);
    }

    public function deleteSize($size)
    {
        $deleteSize = $this->product->childrenOnlyColors()
            ->where('size_id', $size)
            ->delete();

        return $this->redirectRoute('admin.product.delete-attributes', $this->productId);
    }

    public function render()
    {
        $attributeColor = !empty($this->filters) ? Color::findOrFail($this->filters[0]) : '';
        $attributeSize = !empty($this->filtersz) ? Size::findOrFail($this->filtersz[0]) : '';

        $attributes = Product::with('children')->findOrFail($this->productId);
        return view('backend.product.livewire.delete-attributes')->with(compact('attributes', 'attributeColor', 'attributeSize'));
    }
}

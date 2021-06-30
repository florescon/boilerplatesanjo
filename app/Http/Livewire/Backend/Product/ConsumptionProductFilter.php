<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Collection;

class ConsumptionProductFilter extends Component
{
	public $product_id, $color_id, $size_id;


    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->color_id = $product->color_id;
        $this->size_id = $product->size_id;
    }

    public function applyColorFilter($product)
    {
        if ($product->color_id) {

        		$color = $product->color_id;

                $product->with(['consumption_filter' => function ($query) use ($color) {
                    $query->where('color_id', $color);
                }]);
        }

        return null;
    }


    public function render()
    {

        $model = Product::with('parent', 'consumption_filter'
    			)->findOrFail($this->product_id);


        $modell = Product::with(['parent', 'consumption_filter'
	        	=> function ($query) {
                            $query->where([['consumptions.color_id', null], ['consumptions.size_id', null]])->orWhere('consumptions.color_id', $this->color_id)->orWhere('consumptions.size_id', $this->size_id);
                            // ->selectRaw('*, quantity as sum');
                            // $query->where('color_id', null);
                        }
    			])->findOrFail($this->product_id);

        $grouped = $modell->consumption_filter->groupBy('material_id');
        
        $groups = new Collection;
        foreach($grouped as $key => $item) {
            $groups->push([
                'material_id' => $item[0]->material->full_name,
                'quantity' => $item->sum('quantity'),
            ]);
        }

        // dd($groups);


        return view('backend.product.livewire.consumption_filter')->with(compact('model', 'groups'));
    }
}

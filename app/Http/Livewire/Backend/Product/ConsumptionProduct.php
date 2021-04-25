<?php

namespace App\Http\Livewire\Backend\Product;

use Illuminate\Support\Collection;
use Livewire\Component;
use App\Models\Product;
use App\Models\Color;
use App\Models\Consumption;

class ConsumptionProduct extends Component
{

public  $product_id, $updateQuantity, $inputquantities, $inputquantities_difference, $name_color;

    public $material_id = [];
    public $filters = [];

    protected $queryString = [
        'updateQuantity' => ['except' => FALSE],
    ];

    protected $listeners = ['filterByTag' => 'filterByTag', 'store', 'clearAll' => '$refresh'];

    public function mount(Product $product)
    {
        $this->product_id = $product->id;
    }

    public function quantities(int $product_id): void
    {

        $this->validate([
            'inputquantities.*.consumption' => 'numeric|sometimes',
            'inputquantities_difference.*.consumption' => 'numeric|sometimes',
        ]);

        // dd($this->inputquantities);

        if($this->inputquantities){
            foreach($this->inputquantities as $key => $productos){
                if(isset($productos['consumption']))
                {
                    $consumption_update = Consumption::where('id', $key)->first();
                    $consumption_update->update(['quantity' => $productos['consumption']]);
                }
            }
        }



        if($this->inputquantities_difference){
            foreach($this->inputquantities_difference as $key => $productos){
                if(isset($productos['consumption']))
                {

                    $cons_dif_UpOrCre = Consumption::where('id', $key)->first();

                    $cons_upd_quant = Consumption::updateOrCreate([
                        'product_id' => $cons_dif_UpOrCre->product_id,
                        'material_id' => $cons_dif_UpOrCre->material_id,
                        'color_id' => $this->filters[0] ?? null,
                    ]);

                    // dd($cons_upd_quant);

                    $cons_upd_quant->update(['quantity' => $productos['consumption']]);
                }
            }
        }

        $this->emit('clearAll');
        $this->clearAll();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Amount changed'), 
        ]);
    }


    public function clearAll()
    {
        $this->inputquantities = [];
        $this->inputquantities_difference = [];
        // $this->updateQuantity = FALSE;
    }


    public function store()
    {

        $product = Product::findOrFail($this->product_id);

        $this->validate([
            'material_id' => 'required',
        ]);

        foreach($this->material_id as $material){        

        	if(!$product->consumption->contains('material_id', $material)){
	        	{	
		            $product->consumption()->saveMany([
		                new Consumption([
		                    'product_id' => $this->product_id ,
		                    'material_id' => $material,
                            'color_id' => $this->filters[0] ?? null, 
		                ]),
		            ]);
	    		}
			}	    		
        }

        $this->emit('materialReset');
        // $this->initmodel($product); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);

    }

    // private function initmodel(Product $product)
    // {
    // 	$this->model = Product::with('children', 'consumption')
    //         ->findOrFail($product->id);
    // }

    public function filterByTag($color)
    {

        if (in_array($color, $this->filters)) {
            $ix = array_search($color, $this->filters);
            unset($this->filters[$ix]);

                $this->name_color = '';

        } else {
            $this->filters[] = $color;

            if(count($this->filters) >= 2){
                array_shift($this->filters);
            };

            $this->clearAll();
    
        }

    }



    public function applyColorFilter($product)
    {
        if ($this->filters) {
            foreach ($this->filters as $filter) {     
                // $filter = $this->filters;

                $product->with(['consumption' => function ($query) use ($filter) {
                    $query->where('color_id', $filter)->orWhere('color_id', null);
                    // ->groupBy('material_id')
                    // ->selectRaw('*, sum(quantity) as sum');
                }]);
            }


            $this->name_color = Color::find($this->filters[0])->name;

        }

        return null;
    }



    public function render()
    {

        $model = Product::with(['children', 'consumption'
                    => function ($query) {
                            $query->where('color_id', null);
                            // ->selectRaw('*, quantity as sum');
                            // $query->where('color_id', null);
                        }
                ]);

        $this->applyColorFilter($model);

        $model = $model
                ->findOrFail($this->product_id);

        $grouped = $model->consumption->groupBy('material_id');

        $groups = new Collection;
        foreach($grouped as $key => $item) {
            $groups->push([
                'material_id' => $item[0]->material->full_name,
                'quantity' => $item->sum('quantity'),
            ]);
        }

        return view('backend.product.livewire.consumption')->with(compact('model', 'groups', 'grouped'));
    }

}

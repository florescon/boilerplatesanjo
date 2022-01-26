<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;

class PricesProduct extends Component
{
    public $product_id, $product_name, $product_code, $update;

    public $product_price, $product_average_wholesale_price, $product_wholesale_price;

    public $retail_price, $average_wholesale_price, $wholesale_price;

    public $productModel;

    public bool $customCodes = false;
    public bool $customPrices = false;

    protected $queryString = [
        'customCodes' => ['except' => FALSE],
        'customPrices' => ['except' => FALSE],
    ];

    protected $listeners = ['save' => '$refresh', 'saveAfterUpdate'=> 'render'];

    protected $rules = [
        'productModel.*.price' => 'nullable|not_in:0|regex:/^\d{1,13}(\.\d{1,4})?$/',
        'productModel.*.average_wholesale_price' => 'nullable|not_in:0|regex:/^\d{1,13}(\.\d{1,4})?$/',
        'productModel.*.wholesale_price' => 'nullable|not_in:0|regex:/^\d{1,13}(\.\d{1,4})?$/',
    ];

    protected $messages = [
        'productModel.*.price.not_in' => 'No se permite cero en un precio menudeo',
        'productModel.*.price.regex' => 'Valor no permitido en un precio menudeo',
        'productModel.*.average_wholesale_price.not_in' => 'No se permite cero en un precio medio mayoreo',
        'productModel.*.average_wholesale_price.regex' => 'Valor no permitido en un precio medio mayoreo',
        'productModel.*.wholesale_price.not_in' => 'No se permite cero en un precio mayoreo',
        'productModel.*.wholesale_price.regex' => 'Valor no permitido en un precio mayoreo',
    ];
    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->product_slug = $product->slug;

        $product->load('children.parent');

        $this->productModel = $product->children;

        $this->product_name = $product->name;
        $this->product_code = $product->code;
        $this->product_price = $product->price;
        $this->product_average_wholesale_price = $product->average_wholesale_price ?? __('undefined');
        $this->product_wholesale_price = $product->wholesale_price ?? __('undefined');
    }

    public function save()
    {
        $this->validate([
            'productModel.*.price' => 'nullable|not_in:0|regex:/^\d{1,13}(\.\d{1,4})?$/',
            'productModel.*.average_wholesale_price' => 'nullable|not_in:0|regex:/^\d{1,13}(\.\d{1,4})?$/',
            'productModel.*.wholesale_price' => 'nullable|not_in:0|regex:/^\d{1,13}(\.\d{1,4})?$/',
        ]);

        foreach ($this->productModel as $subprod) {
            if($subprod->isDirty('price')){
                if($subprod->price != null){
                    $subprod->update();
                }
                else{
                    $subprod->update(['price' => null]);
                }
            }
            if($subprod->isDirty('average_wholesale_price')){
                if($subprod->average_wholesale_price != null){
                    $subprod->update();
                }
                else{
                    $subprod->update(['average_wholesale_price' => null]);
                }
            }
            if($subprod->isDirty('wholesale_price')){
                if($subprod->wholesale_price != null){
                    $subprod->update();
                }
                else{
                    $subprod->update(['wholesale_price' => null]);
                }
            }

            if($subprod->price == 0){
                $subprod->update(['price' => null]);
            }
            if($subprod->wholesale_price == 0){
                $subprod->update(['wholesale_price' => null]);
            }
            if($subprod->average_wholesale_price == 0){
                $subprod->update(['average_wholesale_price' => null]);
            }
        }

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
       
        $this->clearPrices();
    }
 
    public function saveAverageWholesaleList()
    {
        $this->validate([
            'productModel.*.average_wholesale_price' => 'nullable|regex:/^\d{1,13}(\.\d{1,4})?$/',
        ]);

        foreach ($this->productModel as $subprod) {
            if($subprod->isDirty('average_wholesale_price')){
                if($subprod->average_wholesale_price != null){
                    $subprod->update();
                }
                else{
                    $subprod->update(['average_wholesale_price' => null]);
                }
            }
        }

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
       
        $this->clearPrices();
    }

    public function saveWholesaleList()
    {
        $this->validate([
            'productModel.*.wholesale_price' => 'nullable|regex:/^\d{1,13}(\.\d{1,4})?$/',
        ]);

        foreach ($this->productModel as $subprod) {
            if($subprod->isDirty('wholesale_price')){
                if($subprod->wholesale_price != null){
                    $subprod->update();
                }
                else{
                    $subprod->update(['wholesale_price' => null]);
                }
            }
        }

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
       
        $this->clearPrices();
    }

    private function initretailprice(Product $product)
    {
        $this->product_price = number_format((float)$product->price, 2);
    }
    private function initaveragewholesaleprice(Product $product)
    {
        $this->product_average_wholesale_price = number_format((float)$product->average_wholesale_price, 2);
    }
    private function initwholesaleprice(Product $product)
    {
        $this->product_wholesale_price = number_format((float)$product->wholesale_price, 2);
    }

    public function saveRetail(bool $clear = false)
    {
        // dd($clear);

        $this->validate([
            'retail_price' => 'regex:/^\d{1,13}(\.\d{1,4})?$/',
        ]);
        
        $save_retail = Product::find($this->product_id);

        if($clear == true){
            $save_retail->children()->update(['price' => null]);            
        }

        $save_retail->update([
            'price' => $this->retail_price ?? null,
        ]);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved retail price'), 
        ]);

        $this->emit('saveAfterUpdate');

        $this->initretailprice($save_retail);
    }

    public function saveAverageWholesale(bool $clear = false)
    {
        $this->validate([
            'average_wholesale_price' => 'regex:/^\d{1,13}(\.\d{1,4})?$/',
        ]);
        
        $save_average_wholesale = Product::find($this->product_id);
        
        if($save_average_wholesale == true){
            $save_average_wholesale->children()->update(['average_wholesale_price' => null]);
        }

        $save_average_wholesale->update([
            'average_wholesale_price' => $this->average_wholesale_price ?? null,
        ]);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved average wholesale price'), 
        ]);
        $this->emit('saveAfterUpdate');

        $this->initaveragewholesaleprice($save_average_wholesale);
    }

    public function saveWholesale(bool $clear = false)
    {
        $this->validate([
            'wholesale_price' => 'regex:/^\d{1,13}(\.\d{1,2})?$/',
        ]);
        
        $save_wholesale = Product::find($this->product_id);

        if($clear == true){
            $save_wholesale->children()->update(['wholesale_price' => null]);            
        }

        $save_wholesale->update([
            'wholesale_price' => $this->wholesale_price ?? null,
        ]);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved wholesale price'), 
        ]);

        $this->emit('saveAfterUpdate');

        $this->initwholesaleprice($save_wholesale);
    }

    public function clearPrices()
    {
        $this->customPrices = FALSE;
    }

    public function updatedCustomCodes()
    {
        $this->customPrices = FALSE;
    }

    public function updatedCustomPrices()
    {
        $this->customCodes = FALSE;
    }

    public function render()
    {
        // $model = Product::with('children.parent')->findOrFail($this->product_id);
        // $parents = $model->children->toArray();

        return view('backend.product.livewire.prices');
    }
}

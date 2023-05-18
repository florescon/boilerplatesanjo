<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;

class PricesProduct extends Component
{
    public $product_id, $product_name, $product_code, $update;

    public $product_price, $product_average_wholesale_price, $product_wholesale_price, $product_special_price;

    public $retail_price, $average_wholesale_price, $wholesale_price, $special_price;

    public $productModel;

    public $priceIVA;

    public $originalPrice;

    public $price;
    public $nameStock;

    public bool $customCodes = false;
    public bool $customPrices = false;

    public bool $switchIVA = false;

    protected $queryString = [
        'customCodes' => ['except' => FALSE],
        'customPrices' => ['except' => FALSE],
    ];

    protected $listeners = ['save' => '$refresh', 'saveAfterUpdate'=> 'render'];

    protected $rules = [
        'price' => 'required|numeric|min:1',
        'productModel.*.price' => 'nullable|not_in:0',
        'productModel.*.average_wholesale_price' => 'nullable|not_in:0',
        'productModel.*.wholesale_price' => 'nullable|not_in:0',
        'productModel.*.special_price' => 'nullable|not_in:0',
    ];

    protected $messages = [
        'productModel.*.price.not_in' => 'No se permite cero en un precio menudeo',
        'productModel.*.price.regex' => 'Valor no permitido en un precio menudeo',
        'productModel.*.average_wholesale_price.not_in' => 'No se permite cero en un precio medio mayoreo',
        'productModel.*.average_wholesale_price.regex' => 'Valor no permitido en un precio medio mayoreo',
        'productModel.*.wholesale_price.not_in' => 'No se permite cero en un precio mayoreo',
        'productModel.*.wholesale_price.regex' => 'Valor no permitido en un precio mayoreo',
        'productModel.*.special_price.not_in' => 'No se permite cero en un precio especial',
        'productModel.*.special_price.regex' => 'Valor no permitido en un precio especial',
    ];
    public function mount(Product $product, string $nameStock = null)
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
        $this->product_special_price = $product->special_price ?? __('undefined');

        $this->nameStock = $nameStock;

    }

    public function save()
    {
        $this->validate([
            'productModel.*.price' => 'nullable|not_in:0',
            'productModel.*.average_wholesale_price' => 'nullable|not_in:0',
            'productModel.*.wholesale_price' => 'nullable|not_in:0',
            'productModel.*.special_price' => 'nullable|not_in:0',
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
            if($subprod->isDirty('special_price')){
                if($subprod->special_price != null){
                    $subprod->update();
                }
                else{
                    $subprod->update(['special_price' => null]);
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
            if($subprod->special_price == 0){
                $subprod->update(['special_price' => null]);
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
            'productModel.*.average_wholesale_price' => 'nullable',
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
            'productModel.*.wholesale_price' => 'nullable',
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
        $this->product_price = number_format($product->price, 2);
    }
    private function initaveragewholesaleprice(Product $product)
    {
        $this->product_average_wholesale_price = number_format($product->average_wholesale_price, 2);
    }
    private function initwholesaleprice(Product $product)
    {
        $this->product_wholesale_price = number_format($product->wholesale_price, 2);
    }
    private function initspecialprice(Product $product)
    {
        $this->product_special_price = number_format($product->special_price, 2);
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

    public function saveSpecial(bool $clear = false)
    {
        $this->validate([
            'special_price' => 'regex:/^\d{1,13}(\.\d{1,2})?$/',
        ]);
        
        $save_special = Product::find($this->product_id);

        if($clear == true){
            $save_special->children()->update(['special_price' => null]);            
        }

        $save_special->update([
            'special_price' => $this->special_price ?? null,
        ]);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved wholesale price'), 
        ]);

        $this->emit('saveAfterUpdate');

        $this->initspecialprice($save_special);
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

    public function calculateIVA()
    {
        if($this->price){
            $this->priceIVA = $this->originalPrice + ((setting('iva') / 100) * $this->originalPrice);
        }
    }

    public function calculatePrice()
    {
        $priceRetaiPrice = getPriceValue($this->price, 'retail_price_percentage');

        // $this->retail_price = setting('round') ? ceil($priceRetaiPrice / 5) * 5 : $priceRetaiPrice;
        $this->retail_price = $priceRetaiPrice;

        // $this->retail_price = $this->retail_price + ((setting('iva') / 100) * $this->retail_price);
        $this->retail_price = number_format($this->retail_price, 2);
    }

    public function calculateAverageWholesalePrice()
    {
        $priceAverageWholesalePrice = getPriceValue($this->price, 'average_wholesale_price_percentage');

        $this->average_wholesale_price = setting('round') ? ceil($priceAverageWholesalePrice / 5) * 5 : $priceAverageWholesalePrice;

        // $this->average_wholesale_price = $this->average_wholesale_price + ((setting('iva') / 100) * $this->average_wholesale_price);
        $this->average_wholesale_price = number_format($this->average_wholesale_price, 2);
    }

    public function calculateWholesalePrice()
    {
        $priceWholesalePrice = $priceWholesalePrice = getPriceValue($this->price, 'wholesale_price_percentage');;

        $this->wholesale_price = setting('round') ? ceil($priceWholesalePrice / 5) * 5 : $priceWholesalePrice;

        // $this->wholesale_price = $this->wholesale_price + ((setting('iva') / 100) * $this->wholesale_price);
        $this->wholesale_price = number_format($this->wholesale_price, 2);
    }

    public function calculateSpecialPrice()
    {
        $priceSpecial = getPriceValue($this->price, 'special_price_percentage');

        // $this->special_price = setting('round') ? ceil($priceSpecial / 5) * 5 : $priceSpecial;
        $this->special_price = $priceSpecial;

        // $this->special_price = $this->special_price + ((setting('iva') / 100) * $this->special_price);
        $this->special_price = number_format($this->special_price, 2);
    }

    public function updatedPrice()
    {
        $this->originalPrice = $this->price;

        $this->calculatePrice();
        $this->calculateAverageWholesalePrice();
        $this->calculateWholesalePrice();
        $this->calculateSpecialPrice();
    }

    public function updatedSwitchIVA()
    {
        if($this->switchIVA){
            $this->calculatePrice();
            $this->calculateAverageWholesalePrice();
            $this->calculateWholesalePrice();
            $this->calculateSpecialPrice();
        }
        else{
            $this->priceIVA = null;

            $this->price = $this->originalPrice;

            $this->retail_price = $this->originalPrice + ((setting('retail_price_percentage') / 100) * $this->originalPrice);
            // $this->calculateIVATypePrice($this->retail_price, 'retail_price');
            // $this->retail_price = $this->retail_price + ((setting('iva') / 100) * $this->retail_price);
            $this->retail_price = number_format($this->retail_price, 2);

            $this->average_wholesale_price = $this->originalPrice + ((setting('average_wholesale_price_percentage') / 100) * $this->originalPrice);
            // $this->average_wholesale_price = $this->average_wholesale_price + ((setting('iva') / 100) * $this->average_wholesale_price);
            $this->average_wholesale_price = number_format($this->average_wholesale_price, 2);

            $this->wholesale_price = $this->originalPrice + ((setting('wholesale_price_percentage') / 100) * $this->originalPrice);
            // $this->wholesale_price = $this->wholesale_price + ((setting('iva') / 100) * $this->wholesale_price);
            $this->wholesale_price = number_format($this->wholesale_price, 2);

            $this->special_price = $this->originalPrice + ((setting('special_price_percentage') / 100) * $this->originalPrice);
            // $this->special_price = $this->special_price + ((setting('iva') / 100) * $this->special_price);
            $this->special_price = number_format($this->special_price, 2);
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        // $model = Product::with('children.parent')->findOrFail($this->product_id);
        // $parents = $model->children->toArray();

        return view('backend.product.livewire.prices');
    }
}


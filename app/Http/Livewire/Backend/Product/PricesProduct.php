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

    protected $listeners = ['save' => '$refresh'];

    protected $rules = [
        'productModel.*.price' => 'nullable|regex:/^\d{1,13}(\.\d{1,4})?$/',
        // 'productModel.*.code' => 'nullable|unique:products',
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
        $this->validate();

        foreach ($this->productModel as $subprod) {
            if($subprod->isDirty('price')){
                $subprod->update();
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

    public function saveRetail()
    {
        $this->validate([
            'retail_price' => 'regex:/^\d{1,13}(\.\d{1,4})?$/',
        ]);
        
        $save_retail = Product::find($this->product_id);
        $save_retail->update([
            'price' => $this->retail_price ?? null,
        ]);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved retail price'), 
        ]);

       $this->initretailprice($save_retail);
    }

    public function saveAverageWholesale()
    {
        $this->validate([
            'average_wholesale_price' => 'regex:/^\d{1,13}(\.\d{1,4})?$/',
        ]);
        
        $save_average_wholesale = Product::find($this->product_id);
        $save_average_wholesale->update([
            'average_wholesale_price' => $this->average_wholesale_price ?? null,
        ]);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved average wholesale price'), 
        ]);

       $this->initaveragewholesaleprice($save_average_wholesale);
    }

    public function saveWholesale()
    {
        $this->validate([
            'wholesale_price' => 'regex:/^\d{1,13}(\.\d{1,2})?$/',
        ]);
        
        $save_wholesale = Product::find($this->product_id);
        $save_wholesale->update([
            'wholesale_price' => $this->wholesale_price ?? null,
        ]);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved wholesale price'), 
        ]);

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

    // public function updateCode()
    // {

    //     $this->validate([
    //         'update.*.code' => 'numeric|sometimes',
    //     ]);

    //     dd($this->update);

    //     if($this->updateCode){
    //         foreach($this->updateCode as $key => $productos){
    //             if(!empty($productos['code']))
    //             {
    //                 $updateC = Product::where('id', $key)->first();
    //                 $updateC->update(['code' => $prdouct['code']]);
    //             }
    //         }
    //     }
    // }

    public function render()
    {
        // $model = Product::with('children.parent')->findOrFail($this->product_id);
        // $parents = $model->children->toArray();

        return view('backend.product.livewire.prices');
    }
}

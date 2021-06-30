<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;

class PricesProduct extends Component
{
    public $product_id, $product_name, $product_code, $product_price, $update;

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

        $product->load('children');

        $this->productModel = $product->children;

        $this->product_name = $product->name;
        $this->product_code = $product->code;
        $this->product_price = $product->price;
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


    public function clearPrices()
    {
        $this->customPrices = FALSE;
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

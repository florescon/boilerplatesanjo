<?php

namespace App\Http\Livewire\Backend\Cartdb;

use Livewire\Component;
use App\Domains\Auth\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Summary;
use Illuminate\Support\Facades\Auth;
use DB;

class SearchProducts extends Component
{
    public ?string $type = '';

    public ?int $branchId = 0;

    public $query;
    public $filterColor = [];
    public $filterSize = [];
    public $selectedProduct = null;

    public $inputformat;

    protected $listeners = ['searchproduct', 'filterByColor' => 'filterByColor', 'filterBySize' => 'filterBySize'];

    protected $messages = [
        'inputformat.*.*.not_in' => 'No se permiten ceros',
        'inputformat.*.*.regex' => 'Valor no permitido',
        'inputformat.*.*.numeric' => 'Debe ser un número',
        'inputformat.*.*.min' => 'Debe ser un número mayor a 1',
        'inputformat.*.*.max' => 'Debe ser un número menor a 10,000',
    ];

    public function mount(string $typeSearch, ?int $branchIdSearch = 0)
    {
        $this->type = $typeSearch;
        $this->branchId = $branchIdSearch;
        $this->reset_search();
    }

    public function updatedQuery()
    {
        $this->products = Product::with('parent', 'brand', 'color', 'size')
            ->whereRaw("code LIKE \"%$this->query%\"")
            ->orWhereRaw("name LIKE \"%$this->query%\"")
            ->onlyProductsAndServices()
            ->get()->take(30)
            ->toArray();

        if(count($this->products) == 1){
            if($this->products[0]['parent']){
                // dd($this->products[0]['id']);
                $this->selectProduct($this->products[0]['id']);
            }
        }

       $this->selectedProduct = null;
    }

    public function filterByColor($color)
    {
        if (in_array($color, $this->filterColor)) {
            $ix = array_search($color, $this->filterColor);
            unset($this->filterColor[$ix]);
        } else {
            $this->filterColor[] = $color;

            array_shift($this->filterSize);

            if(count($this->filterColor) >= 2){
                array_shift($this->filterColor);
            };
        }
    }

    public function filterBySize($size)
    {
        if (in_array($size, $this->filterSize)) {
            $ix = array_search($size, $this->filterSize);
            unset($this->filterSize[$ix]);
        } else {
            $this->filterSize[] = $size;

            array_shift($this->filterColor);

            if(count($this->filterSize) >= 2){
                array_shift($this->filterSize);
            };
        }
    }

    public function format()
    {
        $this->validate([
            'inputformat.*.*' => 'numeric|not_in:0|min:1|max:100000|sometimes',
        ]);

        $getSummary = Summary::getRecordTable($this->type, $this->branchId);

        if($this->inputformat){

            foreach($this->inputformat as $color => $productos){


                while($array = current($productos)){

                    $size = key($productos);

                    $quantity = $productos[$size];

                    $product = Product::where('parent_id', $this->selectedProduct->id)->where('size_id', $size)->where('color_id', $color)->first()->withoutRelations();

                    DB::table('carts')->insert([
                        'product_id' => $product->id,
                        'price' => $product->getPriceWithIva($getSummary->type_price ?? User::PRICE_RETAIL),
                        'price_without_tax' => $product->getPriceWithoutIva($getSummary->type_price ?? User::PRICE_RETAIL),
                        'quantity' => $quantity,
                        'type'=> $this->type,
                        'branch_id' => $this->branchId,
                        'user_id' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    next($productos);
                }
            }
        }

        $this->emit('clearAll');
        $this->clearAll();

        $this->emit('cartUpdated');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
    }

    public function clearAll()
    {
        $this->inputformat = [];
    }

    public function selectProduct($idProduct)
    {
        $product = Product::with('children', 'color', 'size')->findOrFail($idProduct);

        if ($product) {

            $last_record_product = Cart::getLastRecordTable();

            if($last_record_product !== $product->id){

                if($product->isChildren()){
                    $this->addToCart($product->id);
                    $this->emit('swal:alert', [
                        'icon' => 'success',
                        'title'   => $product->full_name, 
                    ]);
                }
                else{
                    if(!$product->isProduct()){
                        $this->insertCart($product->id);
                        $this->emit('swal:alert', [
                            'icon' => 'info',
                            'title'   => __('Service').' '.$product->name, 
                        ]);

                    }
                    else{
                        $this->MainProduct($product->id);
                    }
                }
            }
        }

        $this->emit('cartUpdated');
    }

    private function insertCart($idProduct)
    {
        $product = Product::whereId($idProduct)->first()->withoutRelations();

        DB::table('carts')->insert([
            'product_id' => $idProduct,
            'price' => $product->getPriceWithIva(User::PRICE_RETAIL),
            'price_without_tax' => $product->getPriceWithoutIva(User::PRICE_RETAIL),
            'type'=> $this->type,
            'branch_id' => $this->branchId,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function MainProduct($idProduct)
    {
        $this->reset_search();
        $this->selectedProduct = Product::with('children', 'color', 'size')->findOrFail($idProduct);
        $this->full_name = $this->selectedProduct->full_name;
    }

    public function reset_search()
    {
        $this->query = '';
        $this->products = [];
        $this->selectedProduct = null;
        array_shift($this->filterColor);
        array_shift($this->filterSize);
    }


    public function render()
    {
        $model = null;

        if ($this->filterColor || $this->filterSize) {
            if($this->filterColor){
                foreach ($this->filterColor as $filter) {     
                    $model = Product::with(['children' => function($query) use ($filter){
                            $query->where('color_id', $filter);
                        }]
                    );
                }
            }
            else{
                foreach ($this->filterSize as $filter) {     
                    $model = Product::with(['children' => function($query) use ($filter){
                            $query->where('size_id', $filter);
                        }]
                    );
                }
            }
        }
        else{
            $model = null;
        }
    
        if($this->selectedProduct && ($this->filterColor || $this->filterSize))
        {
            $this->selectedProduct = $model
                ->findOrFail($this->selectedProduct->id);
        }

        // $attributes = Product::with('children')->findOrFail($this->product_id);

        return view('backend.cartdb.search-products')->with(compact('model'));
    }
}

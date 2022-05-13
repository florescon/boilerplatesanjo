<?php

namespace App\Http\Livewire\Backend\Store\Pos;

use Livewire\Component;
use App\Models\Product;
use App\Facades\Cart;

class SearchProduct extends Component
{
    public $query;
    public $products;
    public $selectedProduct = null;
    public $highlightIndex;
    public ?string $full_name = null;
    public $filters = [];
    public $filtersz = [];
    public $match = 'products_sale';

    public function mount()
    {
        $this->reset_search();
    }

    protected $listeners = ['searchproduct', 'filterByColor' => 'filterByColor', 'filterBySize' => 'filterBySize'];

    public function reset_search()
    {
        $this->query = '';
        $this->products = [];
        $this->highlightIndex = 0;
        $this->selectedProduct = null;
        $this->full_name = null;
        array_shift($this->filters);
        array_shift($this->filtersz);
    }

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

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->products) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }
 
    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->products) - 1;
            return;
        }
        $this->highlightIndex--;
    }

    public function dropdown()
    {
        $product = $this->products[$this->highlightIndex] ?? null;
        if ($product) {
            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Selected'), 
            ]);
        }
    }

    public function selectProduct(Product $product)
    {
        if ($product) {

            if($product->parent_id){
                $this->addToCart($product->id, $this->match);
                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => $product->full_name, 
                ]);
            }
            else{
                if(!$product->isProduct()){
                    $this->addToCart($product->id, $this->match);
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

    private function MainProduct($idProduct)
    {
        $this->reset_search();
        $this->selectedProduct = Product::with('children', 'color', 'size')->findOrFail($idProduct);
        $this->full_name = $this->selectedProduct->full_name;
    }

    private function addToCart(int $productId, string $typeCart): void
    {
        Cart::add(Product::
            with(array('parent' => function($query) {
                $query->select('id', 'slug', 'name', 'code', 'price', 'average_wholesale_price', 'wholesale_price', 'file_name');
            }))->get()
            ->find($productId), $typeCart);

        if($typeCart == 'products'){
            $this->emit('productAdded');
        }
        elseif($typeCart == 'products_sale'){
            $this->emit('productAddedSale');
        }

        $this->emit('onProductCartAdded');
    }

    public function updatedQuery()
    {
        $this->products = Product::with('parent', 'color', 'size')
            ->whereRaw("code LIKE \"%$this->query%\"")
            ->orWhereRaw("name LIKE \"%$this->query%\"")
            ->get()->take(30)
            ->toArray();
 
       $this->selectedProduct = null;
    }

    public function render()
    {
        $model = null;

        if ($this->filters || $this->filtersz) {
            if($this->filters){
                foreach ($this->filters as $filter) {     
                    $model = Product::with(['children' => function($query) use ($filter){
                            $query->where('color_id', $filter);
                        }]
                    );
                }
            }
            else{
                foreach ($this->filtersz as $filter) {     
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
    
        if($this->selectedProduct && ($this->filters || $this->filtersz))
        {
            $model = $model
                ->findOrFail($this->selectedProduct->id);
        }

        return view('backend.store.pos.search-product')->with(compact('model'));
    }
}
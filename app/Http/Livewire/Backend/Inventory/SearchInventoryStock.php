<?php

namespace App\Http\Livewire\Backend\Inventory;

use Livewire\Component;
use App\Models\Product;
use App\Models\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class SearchInventoryStock extends Component
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

                if($product->isChildren()){

                    $last_record = DB::table('sessions')->orderByDesc('updated_at')->first();

                    $last_record_product = isset($last_record) ? $last_record->product_id : null;

                    if($last_record_product !== $product->id){
                        if(Session::where('product_id', $product->id)->where('audi_id', Auth::id())->exists()){

                            $productExist = DB::table('sessions')->where('product_id', $product->id)->first();

                            DB::table('sessions')->where('product_id', $product->id)->update(['capture' => $productExist->capture + 1, 'updated_at' => now()]);

                            $this->emit('swal:alert', [
                               'icon' => 'success',
                                'title'   => __('Se sumó'), 
                            ]);
                        }
                        else{

                            DB::table('sessions')->insert([
                                'product_id' => $product->id,
                                'capture' => 1,
                                'stock' => $product->stock,
                                'audi_id' => Auth::id(),
                                'type' => 'stock',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            $this->emit('swal:alert', [
                               'icon' => 'success',
                                'title'   => __('Se insertó producto'), 
                            ]);
                        }
                    }
                }
    
            $this->emit('updatedListener');

            }
            else{
                if(!$product->isProduct()){
                    $this->emit('swal:alert', [
                        'icon' => 'info',
                        'title'   =>  'Los servicios no se usan para inventarios :)', 
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

    public function updatedQuery()
    {
        $this->products = Product::with('parent', 'color', 'size')
            ->whereRaw("code LIKE \"%$this->query%\"")
            ->orWhereRaw("name LIKE \"%$this->query%\"")
            ->get()->take(10)
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

        return view('backend.inventories.livewire.search-inventory-stock')->with(compact('model'));
    }
}

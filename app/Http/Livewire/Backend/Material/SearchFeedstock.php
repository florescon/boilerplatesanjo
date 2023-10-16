<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;
use App\Domains\Auth\Models\User;
use App\Models\Product;
use App\Models\Material;
use App\Models\Cart;
use App\Models\Summary;
use Illuminate\Support\Facades\Auth;
use DB;

class SearchFeedstock extends Component
{
    public ?string $type = '';

    public ?int $branchId = 0;

    public $query;
    public $filterColor = [];
    public $filterSize = [];
    public $selectedProduct = null;

    public $inputformat;

    protected $listeners = ['searchproduct'];

    protected $messages = [
        'inputformat.*.*.not_in' => 'No se permiten ceros',
        'inputformat.*.*.regex' => 'Valor no permitido',
        'inputformat.*.*.numeric' => 'Debe ser un número',
        'inputformat.*.*.min' => 'Debe ser un número mayor a 1',
        'inputformat.*.*.max' => 'Debe ser un número menor a 10,000',
    ];

    public function mount()
    {
        $this->reset_search();
    }

    public function updatedQuery()
    {
        $this->products = Material::with('color', 'size', 'unit')
            ->whereHas('color', function ($qq) {
               $qq->whereRaw("name LIKE \"%$this->query%\"");
            })
            ->orWhere('part_number', 'like', '%' . $this->query . '%')
            ->orWhere('name', 'like', '%' . $this->query . '%')
            ->get()->take(15)
            ->toArray();

       $this->selectedProduct = null;
    }


    public function array_multisum(array $arr): float {
        $sum = array_sum($arr);
        foreach($arr as $child) {
            $sum += is_array($child) ? $this->array_multisum($child) : 0;
        }
        return $sum;
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

                    $product = Product::withTrashed()->where('parent_id', $this->selectedProduct->id)->where('size_id', $size)->where('color_id', $color)->first()->withoutRelations();

                    if($product->trashed()){
                        $product->restore();
                    }

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

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Captured').' '.$this->array_multisum($this->inputformat).' '.__('products'), 
            ]);

        }
        else{
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title'   => 'No puedes capturar datos vacios', 
            ]);
        }


        $this->emit('clearAll');
        $this->clearAll();

        $this->emit('cartUpdated');
    }

    public function clearAll()
    {
        $this->inputformat = [];
    }

    public function selectProduct($idFeedstock)
    {
        $feedstock = Material::with('color', 'size', 'unit')->findOrFail($idFeedstock);


        if ($feedstock) {

            $last_record_product = Cart::getLastRecordTable();

            if($last_record_product !== $feedstock->id){

                $this->insertCart($feedstock->id);

                $this->emit('swal:alert', [
                    'icon' => 'info',
                    'title'   => __('Added').': '.$feedstock->name, 
                ]);

           }
        }

        $this->emit('cartUpdated');
    }

    private function insertCart($idFeedstock)
    {
        $feedstock = Material::whereId($idFeedstock)->first()->withoutRelations();

        DB::table('cart_feedstocks')->insert([
            'material_id' => $idFeedstock,
            'price' => $feedstock->price,
            'type'=> 'out',
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
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

        return view('backend.material.search-feedstock')->with(compact('model'));
    }
}

<?php

namespace App\Http\Livewire\Backend\Additional;

use Livewire\Component;
use App\Domains\Auth\Models\User;
use App\Models\Material;
use App\Models\Additional;
use App\Models\Summary;
use Illuminate\Support\Facades\Auth;
use DB;

class SearchFeedstocks extends Component
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
        'inputformat.*.not_in' => 'No se permiten ceros',
        'inputformat.*.regex' => 'Valor no permitido',
        'inputformat.*.numeric' => 'Debe ser un número',
        'inputformat.*.min' => 'Debe ser un número mayor a 1',
        'inputformat.*.max' => 'Debe ser un número menor a 10,000',
    ];


    public function mount(string $typeSearch, ?int $branchIdSearch = 0)
    {
        $this->type = $typeSearch;
        $this->branchId = $branchIdSearch;
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

    public function clearAll()
    {
        $this->inputformat = [];
    }

    public function selectProduct($idFeedstock)
    {
        $feedstock = Material::with('color', 'size', 'unit')->findOrFail($idFeedstock);

        if ($feedstock) {

            $last_record_product = Additional::getLastRecordTable();

            if($last_record_product !== $feedstock->id){

                // $this->insertCart($feedstock->id);

                $this->openFeedstock($feedstock->id);
     
                // $this->emit('swal:alert', [
                //     'icon' => 'info',
                //     'title'   => __('Added').': '.$feedstock->name, 
                // ]);

           }
        }

        $this->emit('cartUpdated');
    }


    private function openFeedstock($idFeedstock)
    {
        $this->reset_search();
        $this->selectedProduct = Material::with('vendor', 'color', 'size', 'unit', 'family')->findOrFail($idFeedstock);
        $this->full_name = $this->selectedProduct->name;
        $this->price = $this->selectedProduct->price ? '$'.$this->selectedProduct->price : 'undefined price';
        $this->stock = $this->selectedProduct->stock.' '.$this->selectedProduct->unit_name_label;
    }


    private function insertCart($idFeedstock, $quantity)
    {
        $feedstock = Material::whereId($idFeedstock)->first()->withoutRelations();

        DB::table('additionals')->insert([
            'material_id' => $idFeedstock,
            'price' => $feedstock->price,
            'quantity' => $quantity,
            'type'=> $this->type,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function format()
    {
        $this->validate([
            'inputformat.*' => 'numeric|not_in:0|min:1|max:100000|sometimes',
        ]);

        if($this->inputformat){

            foreach($this->inputformat as $key => $q){
                $this->insertCart($key, $q);
            }

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Captured'), 
            ]);
        }

        $this->emit('clearAll');
        $this->clearAll();

        $this->emit('cartUpdated');
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

        return view('backend.additional.search-feedstocks')->with(compact('model'));
    }
}
<?php

namespace App\Http\Livewire\Backend\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Events\Product\ProductCreated;

class CreateProduct extends Component
{
    use WithFileUploads;

    public $name, $code, $description, $photo, $price, $imageName, $photoStatus;

    public ?int $line = null;
    public ?int $brand = null;

    public $priceIVA;

    public $originalPrice;

    public $retail_price;
    public $average_wholesale_price;
    public $wholesale_price;

    public $colors = [];
    public $sizes = [];

    public bool $autoCodes = true;
    public bool $switchIVA = false;

    protected $rules = [
        'name' => 'required|min:3|max:50',
        'code' => 'required|min:3|max:20|regex:/^\S*$/u|unique:products',
        'description' => 'nullable|sometimes',
        'colors' => 'required',
        'sizes' => 'required',
        'photo' => 'image|max:4096|nullable', // 4MB Max
        'price' => 'required|numeric|min:1',
    ];

    private function resetInputFields()
    {
        $this->name = '';
    }

    public function store()
    {
        $this->validate();

        $date = date("Y-m-d");

        if($this->photo){
            $imageName = $this->photo->store("pictures/".$date,'public');
        }

        $product = Product::create([
            'name' => $this->name,                
            'code' => $this->code,
            'description' => $this->description ? $this->description : null,                
            'line_id' => $this->line,                
            'brand_id' => $this->brand,                
            'file_name' => $this->photo ? $imageName : null,
            'price' => $this->retail_price ?? 0,
            'average_wholesale_price' => $this->average_wholesale_price ?? null,
            'wholesale_price' => $this->wholesale_price ?? null,
            'automatic_code' => $this->autoCodes,
        ]);

        $combinations = 0;


        foreach($this->colors as $color){        
            foreach($this->sizes as $size){ 

                $combinations++;

                DB::table('products')->insert([
                    'size_id' => $size,
                    'color_id' => $color,
                    'parent_id' => $product->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // $product->children()->saveMany([
                //     new Product([
                //         'size_id' => $size,
                //         'color_id' => $color,
                //     ]),
                // ]);
            }
        }

        event(new ProductCreated($product));

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => 'Se crearon '.$combinations.' combinaciones de productos', 
        ]);
    }

    public function removePhoto()
    {
        $this->photo = '';
    }

    public function calculateIVA()
    {
        if($this->price){
            $this->priceIVA = $this->originalPrice + ((setting('iva') / 100) * $this->originalPrice);
        }
    }

    public function calculatePrice()
    {
        if($this->switchIVA){
            $this->calculateIVA();
            $priceRetaiPrice = $this->priceIVA + ((setting('retail_price_percentage') / 100) * $this->priceIVA);
        }
        else{
            $priceRetaiPrice = $this->price + ((setting('retail_price_percentage') / 100) * $this->price);
        }

        $this->retail_price = setting('round') ? ceil($priceRetaiPrice / 5) * 5 : $priceRetaiPrice;
    }

    public function calculateAverageWholesalePrice()
    {
        if($this->switchIVA){
            $this->calculateIVA();
            $priceAverageWholesalePrice = $this->priceIVA + ((setting('average_wholesale_price_percentage') / 100) * $this->priceIVA);
        }
        else{
            $priceAverageWholesalePrice = $this->price + ((setting('average_wholesale_price_percentage') / 100) * $this->price);
        }

        $this->average_wholesale_price = setting('round') ? ceil($priceAverageWholesalePrice / 5) * 5 : $priceAverageWholesalePrice;
    }

    public function calculateWholesalePrice()
    {
        if($this->switchIVA){
            $this->calculateIVA();
            $priceWholesalePrice = $this->priceIVA + ((setting('wholesale_price_percentage') / 100) * $this->priceIVA);
        }
        else{
            $priceWholesalePrice = $this->price + ((setting('wholesale_price_percentage') / 100) * $this->price);
        }

        $this->wholesale_price = setting('round') ? ceil($priceWholesalePrice / 5) * 5 : $priceWholesalePrice;
    }

    public function updatedPrice()
    {
        $this->originalPrice = $this->price;

        $this->calculatePrice();
        $this->calculateAverageWholesalePrice();
        $this->calculateWholesalePrice();
    }

    public function updatedSwitchIVA()
    {
        if($this->switchIVA){
            $this->calculatePrice();
            $this->calculateAverageWholesalePrice();
            $this->calculateWholesalePrice();
        }
        else{
            $this->price = $this->originalPrice;

            $this->retail_price = $this->originalPrice + ((setting('retail_price_percentage') / 100) * $this->originalPrice);
            $this->average_wholesale_price = $this->originalPrice + ((setting('average_wholesale_price_percentage') / 100) * $this->originalPrice);
            $this->wholesale_price = $this->originalPrice + ((setting('wholesale_price_percentage') / 100) * $this->originalPrice);
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('backend.product.livewire.create');
    }
}

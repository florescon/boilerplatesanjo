<?php

namespace App\Http\Livewire\Backend\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Events\Product\ProductCreated;
use App\Traits\withProducts;

class CreateProduct extends Component
{
    use WithFileUploads, withProducts;

    public $name, $code, $description, $photo, $price, $imageName, $photoStatus;

    public ?int $line = null;
    public ?int $brand = null;

    public $priceIVA;

    public $originalPrice;

    public $retail_price;
    public $average_wholesale_price;
    public $wholesale_price;
    public $special_price;

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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
            'special_price' => $this->special_price ?? null,
            'cost' => $this->priceIVA ? $this->priceIVA : $this->price,
            'automatic_code' => $this->autoCodes,
            'type' => true,
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

        $this->updateCodes($product);

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
        $priceRetaiPrice = getPriceValue($this->price, 'retail_price_percentage');

        $this->retail_price = $priceRetaiPrice;

        $this->retail_price = number_format($this->retail_price, 2);
    }

    public function calculateAverageWholesalePrice()
    {
        $priceAverageWholesalePrice = getPriceValue($this->price, 'average_wholesale_price_percentage');

        $this->average_wholesale_price = setting('round') ? ceil($priceAverageWholesalePrice / 5) * 5 : $priceAverageWholesalePrice;

        $this->average_wholesale_price = number_format($this->average_wholesale_price, 2);
    }

    public function calculateWholesalePrice()
    {
        $priceWholesalePrice = getPriceValue($this->price, 'wholesale_price_percentage');

        $this->wholesale_price = setting('round') ? ceil($priceWholesalePrice / 5) * 5 : $priceWholesalePrice;

        $this->wholesale_price = number_format($this->wholesale_price, 2);
    }

    public function calculateSpecialPrice()
    {
        $priceSpecial = getPriceValue($this->price, 'special_price_percentage');

        $this->special_price = $priceSpecial;

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

    public function calculateIVATypePrice($typePrice, string $typeString)
    {
        // $typePrice = $typePrice + ((setting('iva') / 100) * $typePrice);
        $typePrice = number_format($typePrice, 2);
    }

    public function render()
    {
        return view('backend.product.livewire.create');
    }
}

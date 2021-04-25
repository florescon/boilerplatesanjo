<?php

namespace App\Http\Livewire\Backend\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;

class CreateProduct extends Component
{

    use WithFileUploads;

    public $name, $code, $description, $color, $size, $photo, $imageName, $photoStatus;
    public $color_id = [];
    public $size_id = [];

    protected $rules = [
        'name' => 'required|min:3',
        'code' => 'required|min:3|unique:products',
        'description' => 'nullable|sometimes',
        'color_id' => 'required',
        'size_id' => 'required',
        'photo' => 'image|max:4096', // 4MB Max
    ];

    private function resetInputFields()
    {
        $this->name = '';
        $this->color = '';        
    }

    public function store()
    {
        $this->validate();

        if($this->photo){
            $imageName = $this->photo->store("images",'public');
        }

        $product = Product::create([
            'name' => $this->name,                
            'code' => $this->code,
            'description' => $this->description ? $this->description : null,                
            'file_name' => $this->photo ? $imageName : null,
            // 'size_id' => $this->size_id,
            // 'color_id' => $this->color_id,
        ]);

        foreach($this->color_id as $color){
            
            foreach($this->size_id as $size){        
                $product->children()->saveMany([
                    new Product([
                        'size_id' => $size,
                        'color_id' => $color,
                    ]),
                ]);
            }
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);

    }

    public function removePhoto()
    {
        $this->photo = '';
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

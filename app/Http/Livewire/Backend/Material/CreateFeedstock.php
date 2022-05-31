<?php

namespace App\Http\Livewire\Backend\Material;

use App\Models\Material;
use App\Models\Color;
use Livewire\Component;
use App\Events\Material\MaterialCreated;
use Illuminate\Support\Facades\Auth;

class CreateFeedstock extends Component
{
    public $part_number, $name, $description, $acquisition_cost, $price, $stock, $unit_id, $color_id, $size_id, $colors;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'part_number' => 'min:3|max:30|nullable|unique:materials',
        'name' => 'required|min:3|max:40',
        'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        'acquisition_cost' => 'nullable|numeric|sometimes|regex:/^\d+(\.\d{1,2})?$/',
        'unit_id' => 'required|numeric',
        'color_id' => 'required|numeric',
        'size_id' => 'nullable|sometimes|numeric',
        'stock' => 'required|numeric',
        'description' => 'min:5|max:100|nullable',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private function resetInputFields()
    {
        $this->part_number = '';
        $this->name = '';
        $this->price = '';
        $this->acquisition_cost = '';
        $this->stock = '';
        $this->description = '';
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        try {
            $this->validate();

            $material = Material::create([
                'part_number' => $this->part_number ? $this->part_number : null,
                'name' => $this->name,                
                'price' => $this->price,                
                'stock' => $this->stock,                
                'acquisition_cost' => $this->acquisition_cost ? $this->acquisition_cost : null,                
                'unit_id' => $this->unit_id,                
                'color_id' => $this->color_id,                
                'size_id' => $this->size_id,                
                'description' => $this->description ? $this->description : null,                

            ]);

            event(new MaterialCreated($material));

            $material->history()->create([
                'old_stock' => 0,
                'stock' => $this->stock,
                'old_price' => 0,
                'price' => $this->price,
                'audi_id' => Auth::id(),
                'comment' => $this->description ?? null,
            ]);
            
            dd('si');

            session()->flash('message', 'The feedstock was successfully created.');
         
            return redirect()->route('admin.material.index');

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating the material.'));
        }
    }

    public function render()
    {
        return view('backend.material.livewire.create-feedstock');
    }
}

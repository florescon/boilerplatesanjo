<?php

namespace App\Http\Livewire\Backend\Material;

use App\Models\Material;
use App\Models\Color;
use Livewire\Component;

class CreateMaterial extends Component
{

    public $part_number, $name, $description, $acquisition_cost, $price, $stock, $unit_id, $color_id, $size_id, $colors;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'part_number' => 'min:3|nullable|unique:materials',
        'name' => 'required|min:3',
        'price' => 'required|numeric',
        'acquisition_cost' => 'nullable|numeric|sometimes',
        'unit_id' => 'required|numeric',
        'color_id' => 'required|numeric',
        'size_id' => 'nullable|sometimes|numeric',
        'stock' => 'required|numeric',
        'description' => 'min:5|nullable',
    ];


    // public function mount()
    // {
    //     $this->colors = Color::select(['id', 'name', 'color'])->orderBy('name')->get();
    // }


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

            Material::create([
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

            $this->resetInputFields();
            $this->emit('materialStore');


    		$this->emit('swal:alert', [
    		    'icon' => 'success',
    		    'title'   => __('Created'), 
    		]);


            $this->emit('materialResetSelect');
        	$this->emitTo('backend.material-table', 'triggerRefresh');
        
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating the material.'));
        }



    }

    public function render()
    {
        return view('backend.material.create-material');
    }

}

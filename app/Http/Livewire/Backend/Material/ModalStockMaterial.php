<?php

namespace App\Http\Livewire\Backend\Material;

use App\Models\Material;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Exception;

class ModalStockMaterial extends Component
{
    public $part_number, $name, $acquisition_cost, $price, $stock;

    public $old_price, $old_stock;

    public $material_id;

    protected $listeners = ['modalUpdateStock'];

    protected $rules = [
        'stock' => 'required|numeric',
        'price' => 'nullable|sometimes|numeric',
    ];

    public function modalUpdateStock(Material $material)
    {
        $this->material_id = $material->id;
        $this->part_number = $material->part_number;
        $this->name = $material->full_name;

        $this->acquisition_cost = $material->acquisition_cost;
        $this->old_price = $material->price;
        $this->old_stock = $material->stock;
    }

    private function resetInputStockFields()
    {
        $this->price = '';
        $this->stock = '';
    }

    public function update()
    {
        try {

            $this->validate();

            $material = Material::findOrFail($this->material_id);

            if($this->stock > 0){
                $material->increment('stock', abs($this->stock));
            }
            else{
                $material->decrement('stock', abs($this->stock));
            }

            if($this->price > 0){
                $material->update(['price' => $this->price]);
            }

            $material->history()->create([
                'old_stock' => $this->old_stock,
                'stock' => $this->stock,
                'old_price' => $this->old_price,
                'price' => $this->price > 0 ? $this->price : $this->old_price,
                'audi_id' => Auth::id(),
            ]);

            $this->resetInputStockFields();

            $this->emit('materialUpdate');

            $this->emitTo('backend.material-table', 'triggerRefresh');
        
            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Created'), 
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating the material.'));
        }
    }

    public function render()
    {
        return view('backend.material.modal-stock-material');
    }
}
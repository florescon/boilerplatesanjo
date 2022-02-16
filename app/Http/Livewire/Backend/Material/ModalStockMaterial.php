<?php

namespace App\Http\Livewire\Backend\Material;

use App\Models\Material;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Exception;
use App\Events\Material\MaterialUpdated;

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

            if($this->price > 0){
                $changed_stock = $material->stock + $this->stock;
                $material->update(['stock' => $changed_stock, 'price' => $this->price]);
                event(new MaterialUpdated($material));

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
            }
            else{
                $this->emit('swal:alert', [
                    'icon' => 'warning',
                    'title'   => 'No puede ser el precio un número negativo :)', 
                ]);
            }

        
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
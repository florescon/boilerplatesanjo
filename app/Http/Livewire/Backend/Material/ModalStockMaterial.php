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
    public $part_number, $name, $acquisition_cost, $price, $stock, $unit;

    public $old_price, $old_stock;

    public $comment;

    public $invoice;

    public $material_id;

    public bool $checkboxInput = false;
    public bool $checkboxOutput = false;

    protected $listeners = ['modalUpdateStock'];

    protected $rules = [
        'stock' => 'required|numeric|gt:0',
        'price' => 'nullable|sometimes|numeric',
        'comment' => 'nullable|sometimes',
        'invoice' => 'nullable|sometimes',
    ];

    public function modalUpdateStock(Material $material)
    {
        $this->material_id = $material->id;
        $this->part_number = $material->part_number;
        $this->name = $material->full_name;
        $this->unit = $material->unit_name_label;
        $this->acquisition_cost = $material->acquisition_cost;
        $this->old_price = $material->price;
        $this->old_stock = $material->stock;
    }

    private function resetInputStockFields()
    {
        $this->price = '';
        $this->stock = '';
        $this->comment = '';
        $this->invoice = '';
        $this->checkboxOutput = false;
        $this->checkboxInput = false;
    }

    public function updatedCheckboxInput()
    {
        $this->checkboxOutput = FALSE;
    }

    public function updatedCheckboxOutput()
    {
        $this->checkboxInput = FALSE;
    }

    public function update()
    {
        $this->validate();

        $material = Material::findOrFail($this->material_id);

        if(!empty($this->stock)){

            $stock = $this->checkboxOutput ? - $this->stock : $this->stock;

            if($this->price > 0 || empty($this->price)){
                $changed_stock = $material->stock + $stock;

                if(empty($this->price)){
                    $material->update(['stock' => $changed_stock]);
                }
                else{
                    $material->update(['stock' => $changed_stock, 'price' => $this->price]);
                }
                event(new MaterialUpdated($material));

                $material->history()->create([
                    'old_stock' => $this->old_stock,
                    'stock' => $stock,
                    'old_price' => $this->old_price,
                    'price' => empty($this->price) ? $this->old_price : $this->price,
                    'audi_id' => Auth::id(),
                    'comment' => $this->comment ?? null,
                    'invoice' => $this->invoice ?? null,
                ]);

                $this->resetInputStockFields();

                $this->emit('materialUpdate');

                $this->emitTo('backend.material-table', 'triggerRefresh');

                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => __('Updated'), 
                ]);
            }
            else{
                $this->emit('swal:alert', [
                    'icon' => 'warning',
                    'title'   => 'No puede ser el precio un número negativo :)', 
                ]);
            }
        }
    }

    public function render()
    {
        return view('backend.material.modal-stock-material');
    }
}
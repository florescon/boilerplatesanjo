<?php

namespace App\Http\Livewire\Backend\Material;

use App\Models\Material;
use Livewire\Component;

class ShowMaterial extends Component
{
    public $part_number, $name, $description, $acquisition_cost, $price, $stock, $unit, $color, $size, $created, $deleted, $updated;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = Material::withTrashed()->findOrFail($id);
        $this->part_number = $record->part_number;
        $this->name = $record->name;

        $this->description = $record->description;
        $this->acquisition_cost = $record->acquisition_cost;
        $this->price = $record->price;
        $this->stock = $record->stock;

        $this->unit = $record->unit->name;
        $this->color = $record->color->name;
        $this->size = $record->size->name;

        $this->deleted = $record->deleted_at;

        $this->created = $record->created_at;
        $this->updated = $record->updated_at;

    }

    public function render()
    {
        return view('backend.material.show-material');
    }
}

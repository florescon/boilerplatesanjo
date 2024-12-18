<?php

namespace App\Http\Livewire\Backend\Material;

use App\Models\Material;
use Livewire\Component;

class ShowMaterial extends Component
{
    public $part_number, $name, $description, $acquisition_cost, $price, $stock, $unit, $color, $size, $vendor, $family, $created, $deleted, $updated, $idFeedstock;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = Material::withTrashed()->findOrFail($id);
        $this->idFeedstock = $record->id;
        $this->part_number = $record->part_number;
        $this->name = $record->name;

        $this->description = $record->description;
        $this->acquisition_cost = $record->acquisition_cost;
        $this->price = $record->price;
        $this->stock = $record->stock_formatted;

        $this->unit = optional($record->unit)->name;
        $this->color = optional($record->color)->name;
        $this->size = optional($record->size)->name;

        $this->vendor = optional($record->vendor)->name;
        $this->family = optional($record->family)->name;

        $this->deleted = $record->deleted_at;

        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function render()
    {
        return view('backend.material.show-material');
    }
}

<?php

namespace App\Http\Livewire\Backend\Brand;

use App\Models\Brand;
use Livewire\Component;

class ShowBrand extends Component
{
    public $name, $slug, $website, $description, $created, $updated;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = Brand::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->slug = $record->slug;
        $this->website = $record->website;
        $this->description = $record->description;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function render()
    {
        return view('backend.brand.show-brand');
    }
}
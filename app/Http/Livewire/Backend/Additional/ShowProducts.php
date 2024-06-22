<?php

namespace App\Http\Livewire\Backend\Additional;

use App\Models\ServiceType;
use Livewire\Component;

class ShowProducts extends Component
{
    public $name, $slug, $created, $updated;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = ServiceType::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->slug = $record->slug;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function render()
    {
        return view('backend.additional.show-products');
    }
}

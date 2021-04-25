<?php

namespace App\Http\Livewire\Backend\Unit;

use App\Models\Unit;
use Livewire\Component;

class ShowUnit extends Component
{
    public $name, $slug, $created, $updated;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = Unit::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->slug = $record->slug;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;

    }

    public function render()
    {
        return view('backend.unit.show-unit');
    }

}

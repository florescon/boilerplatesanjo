<?php

namespace App\Http\Livewire\Backend\Family;

use App\Models\Family;
use Livewire\Component;

class ShowFamily extends Component
{
    public $name, $slug, $created, $updated;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = Family::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->slug = $record->slug;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function render()
    {
        return view('backend.family.show-family');
    }
}

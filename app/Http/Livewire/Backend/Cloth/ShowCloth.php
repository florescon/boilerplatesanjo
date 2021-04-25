<?php

namespace App\Http\Livewire\Backend\Cloth;

use App\Models\Cloth;
use Livewire\Component;

class ShowCloth extends Component
{
    public $name, $slug, $created, $updated;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = Cloth::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->slug = $record->slug;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;

    }

    public function render()
    {
        return view('backend.cloth.show-cloth');
    }
}

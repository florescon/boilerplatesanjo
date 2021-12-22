<?php

namespace App\Http\Livewire\Backend\Model;

use App\Models\ModelProduct;
use Livewire\Component;

class ShowModel extends Component
{
    public $name, $slug, $created, $updated;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = ModelProduct::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->slug = $record->slug;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function render()
    {
        return view('backend.model.show-model');
    }
}

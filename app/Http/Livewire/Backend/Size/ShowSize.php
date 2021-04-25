<?php

namespace App\Http\Livewire\Backend\Size;

use App\Models\Size;
use Livewire\Component;

class ShowSize extends Component
{
    public $name, $slug, $created, $updated;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = Size::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->slug = $record->slug;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;

    }

    public function render()
    {
        return view('backend.size.show-size');
    }

}

<?php

namespace App\Http\Livewire\Backend\Line;

use App\Models\Line;
use Livewire\Component;

class ShowLine extends Component
{
    public $name, $slug, $created, $updated;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = Line::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->slug = $record->slug;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;

    }

    public function render()
    {
        return view('backend.line.show-line');
    }
}

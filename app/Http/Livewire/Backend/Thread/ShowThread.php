<?php

namespace App\Http\Livewire\Backend\Thread;

use App\Models\Thread;
use Livewire\Component;

class ShowThread extends Component
{
    public $name, $code, $vendor, $brand, $created, $updated;

    protected $listeners = ['show'];

    public function show($id)
    {
        $record = Thread::withTrashed()->findOrFail($id);
        $this->name = $record->name;
        $this->code = $record->code;
        $this->vendor = optional($record->vendor)->name;
        $this->brand = optional($record->brand)->name;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function render()
    {
        return view('backend.thread.show-thread');
    }
}

<?php

namespace App\Http\Livewire\Backend\Batch;

use Livewire\Component;
use App\Models\Batch;

class EditBatch extends Component
{
    public $batchId;

    protected $listeners = ['editSelectBatch'];

    public function editSelectBatch(Batch $batch)
    {
        $this->batchId = $batch->id;
    }

    public function render()
    {
        $batchOne = Batch::withTrashed()->findOrFail($this->batchId);

        return view('backend.batch.livewire.edit-batch')->with(compact('batchOne'));
    }
}

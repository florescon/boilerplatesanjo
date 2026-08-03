<?php

namespace App\Http\Livewire\Backend\Status;

use Livewire\Component;
use App\Models\Process;
use Illuminate\Support\Facades\DB;

class CreateProcess extends Component
{
    public $name;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:3',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private function resetInputFields()
    {
        $this->name = '';
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        try {
            $this->validate();

            $process = Process::create([
                'name' => $this->name,
            ]);

            $this->resetInputFields();

            $this->emit('processStore');

            $this->emitTo('backend.processes.process-table', 'triggerRefresh');

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Created'), 
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating the process.'));
        }
    }

    public function render()
    {
        return view('backend.status.livewire.create-process');
    }
}

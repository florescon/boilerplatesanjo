<?php

namespace App\Http\Livewire\Backend\Status;

use Livewire\Component;
use App\Models\Operation;
use Illuminate\Support\Facades\DB;

class CreateStatus extends Component
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

            $status = Operation::create([
                'name' => $this->name,
            ]);

            $this->resetInputFields();

            $this->emit('statusStore');

            $this->emitTo('backend.status.status-table', 'triggerRefresh');

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Created'), 
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating the status.'));
        }
    }

    public function render()
    {
        return view('backend.status.livewire.create-status');
    }
}

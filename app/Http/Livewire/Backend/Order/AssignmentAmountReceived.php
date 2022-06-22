<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Assignment;

class AssignmentAmountReceived extends Component
{
    public $assignment_id;
    public $received;

    // public $quantity;
    // public $received_;
    // public $available;

    // public $isOutput;

    protected $listeners = [
        'forceRenderAssignmentAmount' => 'render'
    ];

    protected $rules = [
        'received' => 'required|integer|min:1',
    ];

    public function mount(Assignment $assignment)
    {
        $this->assignment_id = $assignment->id;
        // $this->quantity = $assignment->quantity;
        // $this->received_ = $assignment->received;
        // $this->isOutput = $assignment->isOutput();
        // $this->available = $assignment->available;

        // $this->initreceived($assignment);
        // $this->init($assignment);
    }

    // private function init(Assignment $assignment)
    // {
    //     $this->quantity = $assignment->quantity;
    //     $this->received_ = $assignment->received;
    //     $this->isOutput = $assignment->isOutput();
    //     $this->available = $assignment->available;
    //     $this->received_ = $assignment->received;
    // }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function outputUpdate($assignmentID)
    {
        $assignmentUpd = Assignment::find($assignmentID);
        $quantity = $assignmentUpd->quantity;
        $received = $assignmentUpd->received;
        $assignmentUpd->update([
            'output' => true,
            'received' => $quantity,
        ]);

        $assignmentUpd->history()->create([
            'quantity' => $quantity - $received,
        ]);

        // $this->emit('forceRenderAssignmentAmount');

        $this->emit('save');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);

        // $this->editStock = !$this->editStock;
    }


    private function initreceived(Assignment $assignment)
    {
        // $this->received_ = $assignment->received;
        $this->clearReceived();
    }

    public function clearReceived()
    {
        $this->received = '';
    }

    public function receivedAmount($assignmentID)
    {
        $this->validate();

        $assignmentUpd = Assignment::find($assignmentID);

        $receivedAssignment = $assignmentUpd->received;
        $availableAssignment = $assignmentUpd->available;

        if($this->received > $availableAssignment){
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title'   => __('Check the quantity'), 
            ]);
        }
        else{
            $assignmentUpd->update([
                'received' => $receivedAssignment + $this->received,
            ]);

            $assignmentUpd->history()->create([
                'quantity' => $this->received
            ]);

            $assignment = Assignment::find($assignmentID);

            if($assignment->received == $assignment->quantity){
                $assignment->update([
                    'output' => true,
                ]);
            }

            $this->initreceived($assignment);

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Saved'), 
            ]);
        }

        $this->emit('AmountReceived');
    }

    public function render()
    {
        $assignment = Assignment::findOrFail($this->assignment_id);

        return view('backend.order.livewire.assignment-amount-received')->with(compact('assignment'));
    }
}

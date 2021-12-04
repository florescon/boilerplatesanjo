<?php

namespace App\Http\Livewire\Backend\Store\Finance;

use App\Models\Finance;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateFinance extends Component
{
    public bool $checkboxExpense = false;
    public ?string $name = null;
    public ?string $amount = null;
    public ?string $comment = null;
    public ?string $ticket_text = null;
    public ?string $date = null;

    protected $listeners = ['createmodal'];

    protected $rules = [
        'name' => 'required|min:3',
        'amount' => 'required|numeric|min:1|regex:/^\d*(\.\d{1,2})?$/',
        'comment' => 'sometimes',
        'ticket_text' => 'sometimes',
    ];

    private function resetInputFields()
    {
        $this->name = '';
        $this->amount = '';
        $this->comment = '';
        $this->ticket_text = '';
        $this->date = '';
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $validatedData = $this->validate();

        if($this->date){
            // dd('sii');
        }

        Finance::create([
            'name' => $this->name,
            'amount' => $this->amount,
            'comment' => $this->comment,
            'date_entered' => $this->date ?: today(),
            'ticket_text' => $this->ticket_text,
            'type' => $this->checkboxExpense ? 'expense' : 'income',
            'audi_id' => Auth::id(),
        ]);

        $this->resetInputFields();
        $this->emit('financeStore');


        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);

        $this->emitTo('backend.store.finance-table', 'triggerRefresh');
    }

    public function render()
    {
        return view('backend.store.finance.create-finance');
    }
}

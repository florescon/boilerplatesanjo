<?php

namespace App\Http\Livewire\Backend\Store\Finance;

use App\Models\Finance;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

class CreateFinance extends Component
{
    public bool $checkboxExpense = false;
    public ?string $name = null;
    public ?string $amount = null;
    public ?string $comment = null;
    public ?string $ticket_text = null;
    public ?string $date = null;
    public ?int $payment_method = null;

    protected $listeners = ['selectPaymentMethod', 'selectedCompanyItem', 'createmodal'];

    protected $rules = [
        'name' => 'required|min:3',
        'amount' => 'required|numeric|min:1|regex:/^\d*(\.\d{1,2})?$/',
        'comment' => 'sometimes',
        'ticket_text' => 'sometimes',
        'payment_method' => 'required_with:amount',
    ];

    private function resetInputFields()
    {
        $this->name = '';
        $this->amount = '';
        $this->comment = '';
        $this->ticket_text = '';
        $this->date = '';
        // $this->payment_method = null;
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function selectPaymentMethod($payment_method)
    {
        if ($payment_method)
            $this->payment_method = $payment_method;
        else
            $this->payment_method = null;
    }

    public function store()
    {
        $validatedData = $this->validate();

        Finance::create([
            'name' => $this->name,
            'amount' => $this->amount,
            'comment' => $this->comment,
            'date_entered' => $this->date ?: today(),
            'ticket_text' => $this->ticket_text,
            'type' => $this->checkboxExpense ? 'expense' : 'income',
            'from_store' => true,
            'payment_method_id' => $this->payment_method,
            'audi_id' => Auth::id(),
        ]);

        $this->resetInputFields();
        $this->emit('financeStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);

        $this->emitTo('backend.store.finance-table', 'refreshFinanceTable');
    }

    public function render()
    {
        return view('backend.store.finance.create-finance');
    }
}

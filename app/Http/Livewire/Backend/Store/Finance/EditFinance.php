<?php

namespace App\Http\Livewire\Backend\Store\Finance;

use App\Models\Finance;
use Livewire\Component;

class EditFinance extends Component
{
    public $selected_id, $name, $comment, $ticket_text;

    protected $listeners = ['edit'];

    public function edit($id)
    {
        $this->resetInputFields();

        $record = Finance::withTrashed()->findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->comment = $record->comment;
        $this->ticket_text = $record->ticket_text;
    }

    private function resetInputFields()
    {
        $this->resetValidation();
        $this->name = '';
        $this->comment = '';
        $this->ticket_text = '';
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:1|max:30',
            'comment' => 'nullable|min:1|max:100',
            'ticket_text' => 'nullable|min:1|max:100',
        ]);
        if ($this->selected_id) {
            $record = Finance::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'comment' => $this->comment,
                'ticket_text' => $this->ticket_text,
            ]);
            // $this->resetInputFields();
        }

        $this->emit('financeUpdate');
        $this->emit('refreshFinanceTable');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Actualizado'), 
        ]);
    }

    public function render()
    {
        return view('backend.store.finance.edit-finance');
    }
}

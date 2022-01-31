<?php

namespace App\Http\Livewire\Backend;

use Livewire\Component;

class DateRange extends Component
{
    public $dateInput = '';
    public $dateOutput = '';

    public function updatedDateInput()
    {
        $this->emit('edateInput', $this->dateInput);
    }

    public function updatedDateOutput()
    {
        $this->emit('edateOutput', $this->dateOutput);
    }

    public function clearFilterDate()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->emit('eclearFilterDate');
    }

    public function render()
    {
        return view('backend.date-range');
    }
}
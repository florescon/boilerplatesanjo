<?php

namespace App\Http\Livewire\Backend\Components;

use Livewire\Component;

class SumCaptured extends Component
{
    public ?int $sum = 0;

    protected $listeners = ['calculateSum' => 'calculateSum'];

    public function calculateSum($sum)
    {
        $this->sum = $sum;
    }

    public function render()
    {
        return view('backend.components.sum-captured');
    }
}

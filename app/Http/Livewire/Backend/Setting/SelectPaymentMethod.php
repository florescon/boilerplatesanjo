<?php

namespace App\Http\Livewire\Backend\Setting;

use Livewire\Component;

class SelectPaymentMethod extends Component
{
    public $payment_method_id;

    public bool $clear = false;

    public function render()
    {
        return view('backend.setting.livewire.select-payment-method');
    }
}

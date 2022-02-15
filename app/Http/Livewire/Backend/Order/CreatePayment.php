<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Finance;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Events\Order\OrderPaymentCreated;

class CreatePayment extends Component
{
    public ?string $amount = null;
    public ?string $comment = null;
    public ?string $date = null;
    public ?int $payment_method = null;

    public $orderId;

    protected $listeners = ['selectPaymentMethod', 'createmodal'];

    public function createmodal(int $id)
    {
        $this->orderId = $id;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->amount = '';
        $this->comment = '';
        $this->date = '';
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
        $order = Order::find($this->orderId);

        $this->validate([
            'amount' => 'required|numeric|min:0.01|regex:/^\d*(\.\d{1,2})?$/|max:'.$order->total_payments_remaining,
            'comment' => 'sometimes',
            'payment_method' => 'required_with:amount',
        ]);

        $payment = new Finance([
            'name' => 'pago',
            'amount' => $this->amount,
            'comment' => $this->comment,
            'date_entered' => $this->date ?: today(),
            'type' => 'income',
            'from_store' => true,
            'payment_method_id' => $this->payment_method,
            'audi_id' => Auth::id(),
        ]);

        $order->orders_payments()->save($payment);

        event(new OrderPaymentCreated($order));

        $this->resetInputFields();
        $this->emit('paymentStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);
    }

    public function render()
    {
        return view('backend.order.livewire.create-payment');
    }
}
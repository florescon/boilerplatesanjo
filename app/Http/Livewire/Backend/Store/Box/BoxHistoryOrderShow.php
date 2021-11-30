<?php

namespace App\Http\Livewire\Backend\Store\Box;

use Livewire\Component;
use App\Models\Cash;
use App\Models\PaymentMethod;

class BoxHistoryOrderShow extends Component
{
    public Cash $cash;

    public $limitPerPage = 8;

    public $filterOrder = [];

    protected $listeners = [
        'load-more' => 'loadMore',
        'filterByPaymentOrder' => "filterByPaymentOrder",
    ];
   
    public function loadMore()
    {
        $this->limitPerPage = $this->limitPerPage + 10;
    }

    public function filterByPaymentOrder($payment)
    {
        if (in_array($payment, $this->filterOrder)) {
            $ix = array_search($payment, $this->filterOrder);
            unset($this->filterOrder[$ix]);
        } else {
            $this->filterOrder[] = $payment;

            if(count($this->filterOrder) >= 2){
                array_shift($this->filterOrder);
            };
        }
    }

    public function render()
    {
        $filterOrder = $this->filterOrder;
        return view('backend.store.box.box-history-order-show',[
            'payment_methods' => PaymentMethod::all(),
            'cash_orders' => $this->cash,
            'orders' => 
                $this->cash->orders()->with('user', 'payment')
                ->when($this->filterOrder, function ($query) use ($filterOrder) {
                    $query->where('payment_method_id', $filterOrder);
                })
                ->orderBy('created_at', 'DESC')->paginate($this->limitPerPage),
        ]);
    }
}

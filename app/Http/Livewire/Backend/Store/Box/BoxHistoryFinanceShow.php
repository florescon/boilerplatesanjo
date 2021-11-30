<?php

namespace App\Http\Livewire\Backend\Store\Box;

use Livewire\Component;
use App\Models\Cash;
use App\Models\PaymentMethod;

class BoxHistoryFinanceShow extends Component
{
    public Cash $cash;

    public $limitPerPage = 8;

    public $filter = [];

    protected $listeners = [
        'load-more' => 'loadMore',
        'filterByPayment' => "filterByPayment",
    ];
   
    public function loadMore()
    {
        $this->limitPerPage = $this->limitPerPage + 10;
    }

    public function filterByPayment($payment)
    {
        if (in_array($payment, $this->filter)) {
            $ix = array_search($payment, $this->filter);
            unset($this->filter[$ix]);
        } else {
            $this->filter[] = $payment;

            if(count($this->filter) >= 2){
                array_shift($this->filter);
            };
        }
    }

    public function render()
    {
        $filter = $this->filter;
        return view('backend.store.box.box-history-finance-show',[
            'payment_methods' => PaymentMethod::all(),
            'cash_finances' => $this->cash,
            'finances' => 
                $this->cash->finances()->with('user', 'payment', 'order')
                ->when($this->filter, function ($query) use ($filter) {
                    $query->where('payment_method_id', $filter);
                })
                ->orderBy('created_at', 'DESC')->paginate($this->limitPerPage),
        ]);
    }
}

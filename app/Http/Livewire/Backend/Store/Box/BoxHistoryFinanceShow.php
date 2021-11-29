<?php

namespace App\Http\Livewire\Backend\Store\Box;

use Livewire\Component;
use App\Models\Cash;

class BoxHistoryFinanceShow extends Component
{
    public Cash $cash;

    public $filerPayment;

    public $limitPerPage = 8;

    protected $listeners = [
        'load-more' => 'loadMore',
    ];
   
    public function loadMore()
    {
        $this->limitPerPage = $this->limitPerPage + 10;
    }

    public function render()
    {
        $this->filerPayment = 1;

        return view('backend.store.box.box-history-finance-show',[
            'cash_finances' => $this->cash,
            'finances' => 
                $this->cash->finances()->with('user', 'payment', 'order')
                ->orderBy('created_at', 'DESC')->paginate($this->limitPerPage),
        ]);
    }
}

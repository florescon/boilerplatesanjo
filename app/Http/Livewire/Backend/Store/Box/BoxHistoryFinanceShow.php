<?php

namespace App\Http\Livewire\Backend\Store\Box;

use Livewire\Component;
use App\Models\Finance;
use App\Models\Cash;

class BoxHistoryFinanceShow extends Component
{
    public Cash $cash;

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
        return view('backend.store.box.box-history-finance-show',[
            'finances' => $this->cash->finances()->orderBy('created_at', 'DESC')->paginate($this->limitPerPage),
        ]);
    }
}

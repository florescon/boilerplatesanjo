<?php

namespace App\Http\Livewire\Backend\Store\Box;

use Livewire\Component;
use App\Models\Cash;

class BoxHistoryOrderShow extends Component
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
        return view('backend.store.box.box-history-order-show',[
            'orders' => $this->cash->orders()->orderBy('created_at', 'DESC')->paginate($this->limitPerPage),
        ]);
    }
}

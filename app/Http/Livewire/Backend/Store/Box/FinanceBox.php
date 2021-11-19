<?php

namespace App\Http\Livewire\Backend\Store\Box;

use Livewire\Component;
use App\Models\Finance;

class FinanceBox extends Component
{
    public $limitPerPage = 8;

    protected $listeners = [
        'load-more' => 'loadMore',
    ];
   
    public function loadMore()
    {
        $this->limitPerPage = $this->limitPerPage + 6;
    }

    public function render()
    {
        return view('backend.store.box.finance-box',[
            'finances' => Finance::query()->onlyNullCash()->orderBy('created_at', 'DESC')->paginate($this->limitPerPage),
        ]);
    }
}

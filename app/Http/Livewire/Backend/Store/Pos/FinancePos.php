<?php

namespace App\Http\Livewire\Backend\Store\Pos;

use Livewire\Component;
use App\Models\Finance;

class FinancePos extends Component
{
    public $limitPerPage = 6;

    protected $listeners = [
        'load-more' => 'loadMore',
        'financeStore' => 'render'
    ];
   
    public function loadMore()
    {
        $this->limitPerPage = $this->limitPerPage + 6;
    }

    public function redirectIncomes(){
        return redirect()->route('admin.store.finances.index', ['incomes' => true]);
    }

    public function redirectExpenses(){
        return redirect()->route('admin.store.finances.index', ['expenses' => true]);
    }

    public function render()
    {
        $incomes_today = Finance::query()->today()->onlyIncomes();
        $expenses_today = Finance::query()->today()->onlyExpenses();

        $incomes_yesterday = Finance::query()->yesterday()->onlyIncomes();
        $expenses_yesterday = Finance::query()->yesterday()->onlyExpenses();

        $incomes_week = Finance::query()->currentWeek()->onlyIncomes();
        $expenses_week = Finance::query()->currentWeek()->onlyExpenses();

        $finances = Finance::latest()->paginate($this->limitPerPage);        

        return view('backend.store.pos.finance-pos', [
            'finances' => $finances,
            'incomes_today' => $incomes_today,
            'expenses_today' => $expenses_today,
            'incomes_yesterday' => $incomes_yesterday,
            'expenses_yesterday' => $expenses_yesterday,
            'incomes_week' => $incomes_week,
            'expenses_week' => $expenses_week,
        ]);
    }
}

<?php

namespace App\Http\Livewire\Backend\Store\Box;

use App\Models\Cash;
use App\Models\Order;
use App\Models\Finance;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class HeaderBox extends Component
{
    protected $listeners = [
        'cashStore' => 'render'
    ];

    public $title, $comment;

    public bool $process = false;

    public function process(){
        $this->process = true;
    }

    public function processDailyCashClosing(){

        $this->validate([
            'title' => 'required|max:100',
            'comment' => 'nullable|sometimes',
        ]);

        $last_order_cash = Cash::latest()->first();

        if(!$last_order_cash->checked){
            $last_order_cash->update([
                'title' => $this->title,
                'comment' => $this->comment,
                'audi_id' => Auth::id(),
                'checked' => true
            ]);

            $orders = Order::query()->onlyCashable()->get();
            foreach($orders as $order){
                $order->cashes()->create([
                    'commentable_id' => $order->id,
                    'cash_id' => $last_order_cash->id,
                ]);

                $order->update(['cash_id' => $last_order_cash->id]);
            }

            $finances = Finance::query()->onlyNullCash()->get();
            foreach($finances as $finance){
                $finance->cashes()->create([
                    'commentable_id' => $finance->id,
                    'cash_id' => $last_order_cash->id,
                ]);

                $finance->update(['cash_id' => $last_order_cash->id]);
            }
        }
        
        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);

        sleep(2);

        return redirect()->route('admin.store.box.index');
    }

    public function render()
    {
        $last_order_cash = Cash::latest()->first();

        return view('backend.store.box.header-box',[
            'countOrders' => Order::query()->onlyCashable()->count(),
            'countFinances' => Finance::query()->onlyNullCash()->count(),
            'last_record_cash' => $last_order_cash ? ($last_order_cash->checked == false ? $last_order_cash->initial : '') : '',
        ]);
    }
}

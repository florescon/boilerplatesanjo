<?php

namespace App\Http\Livewire\Backend\Store\Box;

use Livewire\Component;
use App\Models\Order;
use App\Models\Finance;

class OrderBox extends Component
{
    public function render()
    {
        return view('backend.store.box.order-box', [
            'countOrders' => Finance::query()->onlyNullCash()->count(),
            'orders' => Order::query()->onlyCashable()->orderBy('created_at', 'DESC')->paginate(8),
        ]);
    }
}

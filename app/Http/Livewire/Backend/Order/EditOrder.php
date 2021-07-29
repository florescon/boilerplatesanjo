<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Status;
use App\Models\StatusOrder;
use Illuminate\Support\Facades\Auth;

class EditOrder extends Component
{

    public $order_id, $lates_statusId;

    public $previousMaterialByProduct, $maerialAll;

    protected $queryString = [
        'previousMaterialByProduct' => ['except' => FALSE],
        'maerialAll' => ['except' => FALSE],
    ];

    protected $listeners = ['updateStatus' => '$refresh'];

    public function mount(Order $order)
    {
        $this->order_id = $order->id;
        $this->lates_statusId = $order->load('last_status_order')->last_status_order->status_id ?? null;
        $this->initstatus($order);
    }

    public function updateStatus($statusId): void
    {
        if($statusId != $this->lates_statusId){
            StatusOrder::create([
                'order_id' => $this->order_id,
                'status_id' => $statusId,
                'audi_id' => Auth::id(),
            ]);
        }

        $order = Order::findOrFail($this->order_id);
        $this->initstatus($order); // re-initialize the component state with fresh data after saving

        sleep(3);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Status changed'), 
        ]);
    }

    private function initstatus(Order $order)
    {
        $this->lates_statusId = $order->load('last_status_order')->last_status_order->status_id ?? null;
    }

    public function updatedPreviousMaterialByProduct()
    {
        $this->maerialAll = FALSE;
    }

    public function updatedmaerialAll()
    {
        $this->previousMaterialByProduct = FALSE;
    }

    public function render()
    {

        $model = Order::with(['product_order', 'product_sale', 'suborders.user', 'last_status_order', 
                    'materials_order' => function($query){
                        $query->groupBy('material_id')->selectRaw('*, sum(quantity) as sum');
                    }
                ])->findOrFail($this->order_id);

        $statuses = Status::orderBy('level')->get();

        // $collection = collect([
        //     ['item_id' => 10, 'status_id' => 1, 'point' => 3],
        //     ['item_id' => 11, 'status_id' => 5, 'point' => 2],
        //     ['item_id' => 12, 'status_id' => 9, 'point' => 4],
        //     ['item_id' => 13, 'status_id' => 3, 'point' => 1],
        // ]);

        // $grouped = $collection->groupBy('status_id')
        //                     ->map(function ($item) {
        //                         return $item->sum('point');
        //                     });

        // dd($grouped);

        $orderExists = $model->product_order()->exists();
        $saleExists = $model->product_sale()->exists();


        return view('backend.order.livewire.edit')->with(compact('model', 'orderExists', 'saleExists', 'statuses'));
    }
}

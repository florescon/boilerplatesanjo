<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Status;
use App\Models\StatusOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EditOrder extends Component
{

    public $order_id, $lates_statusId, $slug, $isComment, $comment, $isDate, $date_entered;

    public $previousMaterialByProduct, $maerialAll;

    protected $queryString = [
        'previousMaterialByProduct' => ['except' => FALSE],
        'maerialAll' => ['except' => FALSE],
    ];

    protected $listeners = ['updateStatus' => '$refresh'];

    public function mount(Order $order)
    {
        $this->order_id = $order->id;
        $this->slug = $order->slug;
        $this->lates_statusId = $order->load('last_status_order')->last_status_order->status_id ?? null;
        $this->initcomment($order);
        $this->initdate($order);

        $this->initstatus($order);
    }

    private function initcomment(Order $order)
    {
        $this->comment = $order->comment;
        $this->isComment = $order->comment || empty($order) ? $order->comment : __('Define comment');
    }

    private function initdate(Order $order)
    {
        $this->date_entered = $order->date_entered;
        $this->isDate = $order->date_entered || empty($order) ? $order->date_entered : __('Define date');
    }

    public function savecomment()
    {

        $this->validate([
            'comment' => 'required|max:100',
        ]);

        $order = Order::findOrFail($this->order_id);
        $newComment = (string)Str::of($this->comment)->trim()->substr(0, 100); // trim whitespace & more than 100 characters

        $order->comment = $newComment ?? null;
        $order->save();

        $this->initcomment($order); // re-initialize the component state with fresh data after saving



        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }


    public function savedate()
    {

        $this->validate([
            'date_entered' => 'required|max:100',
        ]);

        $order = Order::findOrFail($this->order_id);
        $newDate = (string)Str::of($this->date_entered)->trim()->substr(0, 100); // trim whitespace & more than 100 characters

        $order->date_entered = $newDate ?? null;
        $order->save();

        $this->initdate($order); // re-initialize the component state with fresh data after saving



        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
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

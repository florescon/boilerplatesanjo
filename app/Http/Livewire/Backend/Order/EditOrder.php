<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Status;
use App\Models\StatusOrder;
use App\Models\Product;
use App\Models\OrderStatusDelivery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Events\Order\OrderProductionStatusUpdated;
use App\Events\Order\OrderStatusUpdated;

class EditOrder extends Component
{
    public $order_id, $lates_statusId, $slug, $isComment, $comment, $isDate, $date_entered;

    public $previousMaterialByProduct, $maerialAll;

    public $order_status_delivery;
    public $last_order_delivery;
    public $last_order_delivery_formatted;

    protected $queryString = [
        'previousMaterialByProduct' => ['except' => FALSE],
        'maerialAll' => ['except' => FALSE],
    ];

    protected $listeners = ['updateStatus' => '$refresh', 'paymentStore' => 'render', 'serviceStore' => 'render'];

    public function mount(Order $order)
    {
        $this->order_id = $order->id;
        $this->slug = $order->slug;
        $this->lates_statusId = $order->last_status_order->status_id ?? null;
        $this->initcomment($order);
        $this->initdate($order);

        $this->last_order_delivery = $order->last_order_delivery->type ?? null;
        $this->last_order_delivery_formatted = $order->last_order_delivery->formatted_type ?? null;

        $this->initstatus($order);
    }

    protected $rules = [
        'order_status_delivery' => 'required|min:2',
    ];

    public function updatedOrderStatusDelivery($value)
    {
        $this->validate();

        $order = Order::findOrFail($this->order_id);

        if($this->last_order_delivery != $value){
            $order->orders_delivery()->create(['type' => $value, 'audi_id' => Auth::id()]);

            event(new OrderStatusUpdated($order));

        }

        session()->flash('message', __('The status delivery was successfully changed.'));

        return redirect()->route('admin.order.edit', $this->order_id);

    }

    private function initcomment(Order $order)
    {
        $this->comment = $order->comment;
        $this->isComment = $order->comment || empty($order) ? $order->comment : __('Define comment');
    }

    private function initdate(Order $order)
    {
        $this->date_entered = $order->date_entered;
        $this->isDate = $order->date_entered || empty($order) ? $order->date_entered->format('d-m-Y') : __('Define date');
    }

    public function savecomment()
    {
        $this->validate([
            'comment' => 'required|max:300',
        ]);

        $order = Order::findOrFail($this->order_id);
        $newComment = (string)Str::of($this->comment)->trim()->substr(0, 300); // trim whitespace & more than 100 characters

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

            $order = Order::findOrFail($this->order_id);

            $statusOrder = new StatusOrder([
                'status_id' => $statusId,
                'audi_id' => Auth::id(),
            ]);

            $order->status_order()->save($statusOrder);

            event(new OrderProductionStatusUpdated($order));

            $this->initstatus($order); // re-initialize the component state with fresh data after saving
        }

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

    public function approve()
    {
        Order::whereId($this->order_id)->update(['approved' => true]);
        return $this->redirectRoute('admin.order.edit', $this->order_id);
    }
  
    public function send()
    {
        $order = Order::findOrFail($this->order_id);

        foreach($order->product_order as $product_order){

            $product = Product::withTrashed()->find($product_order->product_id);
            
            if($product_order->quantity > 0){
                if($product->isProduct()){
        
                    $product->history()->create([
                        'product_id' => $product_order->product_id,
                        'stock' => $product_order->quantity,
                        'price' => $product_order->price,
                        'order_id' => $this->order_id,
                        'is_output' => true,
                        'audi_id' => Auth::id(),
                    ]);


                    $product->increment('stock', abs($product_order->quantity));
                }
            }
        }

        Order::whereId($this->order_id)->update(['to_stock' => true]);

        return $this->redirectRoute('admin.order.edit', $this->order_id);
    }

    public function render()
    {
        $model = Order::with(['product_order', 'product_sale', 'suborders.user', 'last_status_order', 
                    'materials_order' => function($query){
                        $query->groupBy('material_id')->selectRaw('*, sum(quantity) as sum, sum(quantity) * price as sumtotal');
                    }
                ])->findOrFail($this->order_id);

        $statuses = Status::orderBy('level')->get();

        $orderExists = $model->product_order()->exists();
        $saleExists = $model->product_sale()->exists();

        $OrderStatusDelivery = OrderStatusDelivery::values();    

        if(!$model->isSuborder()){
            return view('backend.order.livewire.edit')->with(compact('model', 'orderExists', 'saleExists', 'statuses', 'OrderStatusDelivery'));
        }
        else{ 
            return view('backend.order.suborder')->with(compact('model', 'orderExists', 'saleExists', 'statuses', 'OrderStatusDelivery'));           
        }
    }
}
<?php

namespace App\Http\Livewire\Backend\Chart\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Status;
use App\Models\StatusOrder;
use App\Models\Product;
use App\Models\ServiceType;

use App\Models\OrderStatusDelivery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Events\Order\OrderProductionStatusUpdated;
use App\Events\Order\OrderStatusUpdated;
use DB;
use App\Events\Order\OrderDeleted;
use Illuminate\Support\Facades\Validator;

class EditOrder extends Component
{
    public $order_id, $lates_statusId, $slug, $isComment, $comment, $isObservation, $observation, $isComplementary, $complementary, $isNotes, $notes, $isDiscount, $discount, $isRequest, $request, $isPurchase, $purchase, $isInfo_customer, $info_customer, $isDate, $date_entered;

    public $previousMaterialByProduct, $maerialAll;

    public $order_status_delivery;
    public $last_order_delivery;
    public $last_order_delivery_formatted;

    public $from_store = null;

    public bool $width = false;
    public bool $prices = false;
    public bool $breakdown = false;
    public bool $general = false;
    public bool $details = true;

    public Order $order;

    public bool $showPriceWithoutTax = false;

    protected $queryString = [
        'previousMaterialByProduct' => ['except' => FALSE],
        'maerialAll' => ['except' => FALSE],
        'prices' => ['except' => FALSE],
        'breakdown' => ['except' => FALSE],
        'general' => ['except' => FALSE],
        'details' => ['except' => FALSE],
    ];

    protected $listeners = ['updateStatus' => '$refresh', 'cartUpdated' => '$refresh', 'paymentStore' => 'render', 'deleteOrder', 'serviceStore' => 'render'];

    public function mount(Order $order)
    {
        $this->order_id = $order->id;
        $this->slug = $order->slug;
        $this->from_store = $order->from_store;
        $this->lates_statusId = $order->last_status_order->status_id ?? null;
        $this->initcomment($order);
        $this->initobservation($order);
        $this->initcomplementary($order);
        $this->initnotes($order);
        $this->initdiscount($order);
        $this->initrequest($order);
        $this->initpurchase($order);
        $this->initinfo_customer($order);
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

        return redirect()->route($this->from_store ? 'admin.store.all.edit' : 'admin.order.edit', $this->order_id);
    }

    private function initcomment(Order $order)
    {
        $this->comment = $order->comment;
        $this->isComment = $order->comment || empty($order) ? $order->comment : __('Define comment');
    }

    private function initobservation(Order $order)
    {
        $this->observation = $order->observation;
        $this->isObservation = $order->observation || empty($order) ? $order->observation : __('Define observation');
    }

    private function initcomplementary(Order $order)
    {
        $this->complementary = $order->complementary;
        $this->isComplementary = $order->complementary || empty($order) ? $order->complementary : __('Define Complementary observations');
    }

    private function initnotes(Order $order)
    {
        $this->notes = $order->notes;
        $this->isNotes = $order->notes || empty($order) ? $order->notes : __('Define Notes');
    }

    private function initdiscount(Order $order)
    {
        $this->discount = $order->discount;
        $this->isDiscount = $order->discount || empty($order) ? $order->discount.'% '.__('Discount') : __('Define discount');
    }

    private function initrequest(Order $order)
    {
        $this->request = $order->request;
        $this->isRequest = $order->request || empty($order) ? $order->request : __('Define request');
    }

    private function initpurchase(Order $order)
    {
        $this->purchase = $order->purchase;
        $this->isPurchase = $order->purchase || empty($order) ? $order->purchase : __('Define purchase order');
    }

    private function initinfo_customer(Order $order)
    {
        $this->info_customer = $order->info_customer;
        $this->isInfo_customer = $order->info_customer || empty($order) ? $order->info_customer : __('Define info customer');
    }

    private function initdate(Order $order)
    {
        $this->date_entered = $order->date_entered;
        $this->isDate = $order->date_entered || empty($order) ? $order->date_entered->format('d-m-Y') : __('Define date');
    }

    public function saveinfocustomer()
    {
        $this->validate([
            'info_customer' => 'required|max:300',
        ]);

        $order = Order::findOrFail($this->order_id);
        $newInfocustomer = (string)Str::of($this->info_customer)->trim()->substr(0, 300); // trim whitespace & more than 100 characters

        $order->info_customer = $newInfocustomer ?? null;
        $order->save();

        $this->initinfo_customer($order); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
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

    public function saveobservation()
    {
        $this->validate([
            'observation' => 'required|max:300',
        ]);

        $order = Order::findOrFail($this->order_id);
        $newComment = (string)Str::of($this->observation)->trim()->substr(0, 300); // trim whitespace & more than 100 characters

        $order->observation = $newComment ?? null;
        $order->save();

        $this->initobservation($order); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function savecomplementary()
    {
        $this->validate([
            'complementary' => 'required|max:300',
        ]);

        $order = Order::findOrFail($this->order_id);
        $newComplementary = (string)Str::of($this->complementary)->trim()->substr(0, 300); // trim whitespace & more than 100 characters

        $order->complementary = $newComplementary ?? null;
        $order->save();

        $this->initcomplementary($order); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function savenotes()
    {
        $this->validate([
            'notes' => 'required|max:300',
        ]);

        $order = Order::findOrFail($this->order_id);
        $newNotes = (string)Str::of($this->notes)->trim()->substr(0, 300); // trim whitespace & more than 100 characters

        $order->notes = $newNotes ?? null;
        $order->save();

        $this->initnotes($order); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function savediscount()
    {
        $this->validate([
            'discount' => 'integer|min:0|max:100',
        ]);

        $order = Order::findOrFail($this->order_id);
        $order->discount = $this->discount ?? 0;
        $order->save();

        $this->initdiscount($order); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }
    public function saverequest()
    {
        $this->validate([
            'request' => 'required|max:100',
        ]);

        $order = Order::findOrFail($this->order_id);
        $newRequest = (string)Str::of($this->request)->trim()->substr(0, 300); // trim whitespace & more than 100 characters

        $order->request = $newRequest ?? null;
        $order->save();

        $this->initrequest($order); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }
    public function savepurchase()
    {
        $this->validate([
            'purchase' => 'required|max:100',
        ]);

        $order = Order::findOrFail($this->order_id);
        $newPurchase = (string)Str::of($this->purchase)->trim()->substr(0, 300); // trim whitespace & more than 100 characters

        $order->purchase = $newPurchase ?? null;
        $order->save();

        $this->initpurchase($order); // re-initialize the component state with fresh data after saving

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
        if(auth()->user()->can('admin.access.states_production.create')){
        
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
        else
        {
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title'   => __('You do not have access to do that.'), 
            ]);
        }

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

    public function processQuotation()
    {
        $order = Order::whereId($this->order_id)->first();

        $order->touch();

        $last_order_or_request = $order->last_order_or_request;

        $orderUpdate = $order->update(['type' => !$this->from_store ? 1 : 5, 'folio' => $last_order_or_request+1, 'date_entered' => today(), 'created_at' => now()]);
        $order->product_quotation()->update(['type' => !$this->from_store ? 1 : 5]);   

        if($this->from_store){
            $this->requestReadyForDelivery($order);
        }

        return $this->redirectRoute($this->from_store ? 'admin.store.all.edit' : 'admin.order.edit_chart', $this->order_id);
    }

    public function requestReadyForDelivery($order)
    {
        $order ? $order->orders_delivery()->create(['type' => OrderStatusDelivery::PENDING, 'audi_id' => Auth::id()]) : '';
    }

    public function approve()
    {
        Order::whereId($this->order_id)->update(['approved' => true]);
        return $this->redirectRoute($this->from_store ? 'admin.store.all.edit' : 'admin.order.edit', $this->order_id);
    }
  
    public function send()
    {
        $order = Order::findOrFail($this->order_id);

        foreach($order->product_order as $product_order){

            $product = Product::withTrashed()->find($product_order->product_id);
            
            if($product_order->quantity > 0){
                if($product->isProduct()){
        
                    $product->history_subproduct()->create([
                        'product_id' => optional($product->parent)->id ?? null,
                        'stock' => $product_order->quantity,
                        'old_stock' => $product->stock ?? null,
                        'type_stock' => 'stock',
                        'price' => $product_order->price,
                        'order_id' => $this->order_id,
                        'is_output' => false,
                        'audi_id' => Auth::id(),
                    ]);

                    $product->increment('stock', abs($product_order->quantity));
                }
            }
        }

        Order::whereId($this->order_id)->update(['to_stock' => true]);

        return $this->redirectRoute($this->from_store ? 'admin.store.all.edit' : 'admin.order.edit', $this->order_id);
    }


    public function updatePrices(?int $getCustomer = null)
    {
        $order = Order::whereId($this->order_id)->first();

        $customerPrice = optional($order->user->customer)->type_price ?? 'retail';

        foreach($order->product_quotation as $product){

            if($product->product->isProduct()){

                $price = $product->product->getPriceWithIvaRound($customerPrice);
                $priceWithoutIva = $product->product->getPriceWithoutIvaRound($customerPrice);

                $product->update(['price' => $price, 'price_without_tax' => $priceWithoutIva]);
            }
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated'), 
        ]);

    }

    public function removeProduct($productId): void
    {
        $delete = DB::table('product_order')->where('id', $productId)->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function makeDeleteOrder()
    {
        return $this->emit('swal:input', [
            'title' => __('Define the reason for the deletion'),
            'input' => 'text',
            'getId' => $this->order_id,
            'showCancelButton' => true,
            'method' => 'deleteOrder',
            'inputPlaceholder' => __('Enter the reason'), // Cambiado para que sea más claro
        ]);
    }


    public function deleteOrder($order_id, $comment = null)
    {
        $order = Order::findOrFail($order_id);

        if($order->stations()->exists()){
            abort(403, __('Tiene datos asociados') . ' :(');
        }


      // Validar el comentario
        $validator = Validator::make(
            ['comment' => $comment],
            [
                'comment' => 'required|min:6|max:200',
            ],
            [
                'comment.required' => __('El comentario es obligatorio.'),
                'comment.min' => __('El comentario debe tener al menos :min caracteres.'),
                'comment.max' => __('El comentario no debe exceder :max caracteres.'),
            ]
        );

        if ($validator->fails()) {
            $validationErrors = $validator->errors()->all();
            
            $this->emit('swal:modal', [
                'icon' => 'error',
                'title' => __('Errores de validación'),
                'html' => implode('<br>', $validationErrors),
            ]);
            
            return false;
        }

        // Actualizar el comentario antes de eliminar
        if($comment) {
            $order->update(['note_deletes' => $comment]);
        }

        // Eliminar la orden
        $order->delete();

        event(new OrderDeleted($order));

        session()->flash('message', __('The order/sale was successfully deleted'));

        return redirect()->route($order->from_store ? 'admin.store.all.index' : 'admin.order.request_chart_work');
    }

    public function renderButton()
    {
        $this->emit('serviceStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Data saved in this table'), 
        ]);
    }

    public function render()
    {
        $model = Order::with(['product_order', 'product_sale', 'product_request', 'suborders.user', 'last_status_order', 
                    'materials_order' => function($query){
                        $query->groupBy('material_id')->selectRaw('*, sum(quantity) as sum, sum(quantity) * price as sumtotal');
                    }
                ])->findOrFail($this->order_id);

        $statuses = Status::orderBy('level')->get();
        $supplier = Status::orderBy('level')->where('supplier', TRUE)->first();
        $batches = Status::orderBy('level')->where('batch', TRUE)->get();
        $process = Status::orderBy('level')->where('process', TRUE)->get();

        $orderExists = $model->product_order()->exists();
        $saleExists = $model->product_sale()->exists();
        $requestExists = $model->product_request()->exists();
        $quotationExists = $model->product_quotation()->exists();
        $productsOutputExists = $model->product_output()->exists();

        $OrderStatusDelivery = OrderStatusDelivery::values();    

        $tablesData = $model->getSizeTablesData();

        // dd($tablesData);


        // dd($model->total_graphic_work);
        // dd($model->products->toArray())
        // dd($model->getProductsGroupedByParentSizeColor());
        // dd($model->getProductsColorSizeMatrix()->toArray());
        
        $orderServices = DB::table('product_order as a')
                ->selectRaw('
                    b.name as product_name,
                    b.code as product_code,
                    b.color_id as color_name,
                    b.size_id as size_name,
                    b.brand_id as brand_name,
                    min(a.price) as min_price,
                    max(a.price) as max_price,
                    min(a.price) <> max(a.price) as omg,
                    sum(a.quantity) as sum,
                    sum(a.quantity * a.price) as sum_total,
                    a.quantity as total_by_product
                ')
                ->join('products as b', 'a.product_id', '=', 'b.id')
                ->where('order_id', $this->order->id)
                ->where('b.type', '=', 0)
                ->groupBy('b.id', 'a.price')
                ;

        $orderGroup = DB::table('product_order as a')
            ->selectRaw('
                c.name as product_name,
                c.code as product_code,
                d.name as color_name,
                e.name as size_name,
                f.name as brand_name,
                min(a.price) as min_price,
                max(a.price) as max_price,
                min(a.price) <> max(a.price) as omg,
                sum(a.quantity) as sum,
                sum(a.quantity * a.price) as sum_total,
                count(*) as total_by_product
            ')
            ->join('products as b', 'a.product_id', '=', 'b.id')
            ->join('products as c', 'b.parent_id', '=', 'c.id')
            ->join('colors as d', 'b.color_id', '=', 'd.id')
            ->join('sizes as e', 'b.size_id', '=', 'e.id')
            ->join('brands as f', 'c.brand_id', '=', 'f.id')  // Agregamos el join con la tabla brands
            ->groupBy('b.parent_id', 'b.color_id', 'a.price')
            ->where('order_id', $this->order->id)
            ->orderBy('product_name')
            ->orderBy('color_name')
            ->union($orderServices)
            ->get();

        if(!$model->isSuborder()){
            return view('backend.chart.order.edit-order')->with(compact('tablesData', 'orderGroup', 'model', 'orderExists', 'saleExists', 'requestExists', 'quotationExists', 'productsOutputExists', 'statuses', 'batches', 'supplier', 'process', 'OrderStatusDelivery'));
        }
        else{
            return view('backend.order.suborder')->with(compact('model', 'orderExists', 'saleExists', 'requestExists', 'quotationExists', 'productsOutputExists', 'statuses', 'supplier', 'batches', 'process', 'OrderStatusDelivery'));           
        }
    }
}

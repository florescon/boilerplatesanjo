<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Assignment;
use App\Models\Ticket;
use App\Models\Status;
use App\Models\ProductOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Carbon\Carbon;
use Exception;
use App\Events\Order\OrderAssignmentCreated;
use Illuminate\Support\Str;

class AssignmentsOrder extends Component
{
    public $order_id, $status_id, $quantityy, $user, $status_name;

    public $next_status, $previous_status;

    public ?string $date = null;
    public ?string $date_entered = null;

    public $output;

    protected $listeners = ['selectedCompanyItem', 'save' => '$refresh', 'AmountReceived' => 'render'];

    public function mount(Order $order, Status $status)
    {
        $this->order_id = $order->id;
        $this->status_id = $status->id;
        $this->next_status = Status::where('id', '>', $status->id)->where('to_add_users', true)
                ->oldest('level')
                ->first();
        $this->previous_status = Status::where('id', '<', $status->id)->where('to_add_users', true)
                ->latest('level')
                ->first();
        $this->status_name = $status->name;
    }

    protected $rules = [
        'user' => 'required',
    ];

    public function selectedCompanyItem($item)
    {
        if ($item)
            $this->user = $item;
        else
            $this->user = null;
    }

    public function outputUpdateAll($ticketID)
    {
        $ticketUpd = Ticket::find($ticketID);

        $products = $ticketUpd->assignments_direct()->where('output', false)->get();
        
        foreach($products as $product){

            $product->history()->create([
                'quantity' => $product->quantity,
                'user_id' => $ticketUpd->user_id,
                'ticket_id' => $ticketID,
                'audi_id' => Auth::id(),
            ]);

            $product->update(['output' => true]);
        }

        $this->emit('forceRenderAssignmentAmount');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function saveDate($ticketID)
    {
        $this->validate([
            'date_entered' => 'required|max:100',
        ]);

        $ticket = Ticket::findOrFail($ticketID);
        $newDate = (string)Str::of($this->date_entered)->trim()->substr(0, 100); // trim whitespace & more than 100 characters

        $ticket->date_entered = $newDate ?? null;
        $ticket->save();

        $this->date_entered = null;

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
    }

    public function save()
    {
        $this->validate();

        $orderModel = Order::with('products')->find($this->order_id);
        // $orderModel->product_order()->where('id', $this->quantityy[0])->first();

        foreach($orderModel->products as $bal)
        {
            if(is_array($this->quantityy) && array_key_exists($bal->id, $this->quantityy)){
                // dd($bal->available_assignments);
                $this->validate([
                    'quantityy.'.$bal->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$bal->available_assignments,
                ]);
            }
        }

        // dd($this->quantityy);

        DB::beginTransaction();

        try {

            if(!empty($this->quantityy)){

                // dd($this->quantityy);

                // $order = new Ticket();
                // $order->order_id = $this->order_id;
                // $order->status_id = $this->status_id;
                // $order->user_id = $this->user ?? null;
                // // $order->date_entered = Carbon::now()->format('Y-m-d');
                // // $order->audi_id = Auth::id();
                // $order->save();

                $ticket = new Ticket([
                    'status_id' => $this->status_id,
                    'user_id' => $this->user ?? null,
                    'date_entered' => $this->date ?: today(),
                    'audi_id' => Auth::id(),
                ]);

                $orderModel->tickets()->save($ticket);                

                event(new OrderAssignmentCreated($orderModel));

                foreach($this->quantityy as $key => $product){
                    // dd($product['available']);
                    if(!empty($product['available'])){

                        $productOder = ProductOrder::find($key);

                        $productOder->assignments()->create([
                            'order_id' =>  $this->order_id,
                            'ticket_id' =>  $ticket->id,
                            'status_id' =>  $this->status_id,
                            'user_id' =>  $this->user ?? null,
                            'quantity' => $product['available'],
                        ]); 
                    }
                }

                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => __('Saved'), 
                ]);

            }

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem.'));
        }

        DB::commit();

       $this->resetInput();
    }

    public function resetInput()
    {
        $this->quantityy = '';
    }

    public function render()
    {
        $statusId = $this->status_id;

        $model2 = Order::with(['tickets.assignments_direct.assignmentable.product.color', 'tickets.assignments_direct.assignmentable.product.size', 'tickets' 
                            => function($query) use ($statusId){
                                $query->where('status_id', $statusId);
                            },
                    ])->findOrFail($this->order_id);

        return view('backend.order.livewire.assignments')->with(compact('model2'));
    }
}

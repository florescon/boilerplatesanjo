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

class AssignmentsOrder extends Component
{

    public $order_id, $status_id, $quantityy, $user, $status_name;

    public $next_status, $previous_status;

    public $output;

    protected $listeners = ['selectedCompanyItem', 'save' => '$refresh'];

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

    public function outputUpdate($assignmentID)
    {
        $assignmentUpd = Assignment::find($assignmentID);
        $assignmentUpd->update([
            'output' => true,
        ]);

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);

        // $this->editStock = !$this->editStock;
    }


    public function outputUpdateAll($ticketID)
    {
        $ticketUpd = Ticket::find($ticketID);

        $ticketUpd->assignments_direct()->where('output', false)->update(['output' => true]);
        
        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
    }


    public function save()
    {
        $this->validate();

        $orderModel = Order::with('product_order')->find($this->order_id);
        // $orderModel->product_order()->where('id', $this->quantityy[0])->first();

        foreach($orderModel->product_order as $bal)
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

                $order = new Ticket();
                $order->order_id = $this->order_id;
                $order->status_id = $this->status_id;
                $order->user_id = $this->user ?? null;
                // $order->date_entered = Carbon::now()->format('Y-m-d');
                // $order->audi_id = Auth::id();
                $order->save();

                foreach($this->quantityy as $key => $product){
                    // dd($product['available']);
                    if(!empty($product['available'])){

                        $productOder = ProductOrder::find($key);

                        $productOder->assignments()->create([
                            'order_id' =>  $this->order_id,
                            'ticket_id' =>  $order->id,
                            'status_id' =>  $this->status_id,
                            'user_id' =>  $this->user ?? null,
                            'quantity' => $product['available'],
                        ]); 
                    }
                }
            }

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem.'));
        }

        DB::commit();

       $this->resetInput();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
    }

    public function resetInput()
    {
        $this->quantityy = '';
    }

    public function render()
    {
        $statusId = $this->status_id;

        $model2 = Order::with(['tickets.assignments_direct.assignmentable.product', 'tickets' 
                            => function($query) use ($statusId){
                                $query->where('status_id', $statusId);
                            },
                    ])->findOrFail($this->order_id);

        return view('backend.order.livewire.assignments')->with(compact('model2'));
    }
}

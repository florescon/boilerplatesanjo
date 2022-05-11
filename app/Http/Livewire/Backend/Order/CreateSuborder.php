<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Departament;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Events\Order\OrderCreated;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;

class CreateSuborder extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    public $order_id, $quantityy, $departament, $status_name;

    public $perPage = '15';

    public $searchTerm = '';

    public $sortField = 'updated_at';

    public $sortAsc = false;

    protected $listeners = ['selectedDeparament', 'savesuborder' => '$refresh'];

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    protected $rules = [
        'departament' => 'required',
    ];


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function getRowsQueryProperty()
    {
        $query = Product::query()
            ->with('parent', 'color', 'size')
            ->onlySubProducts()
            ->where('stock', '<>', 0)
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            });

        $this->applySearchFilter($query);

        return $query;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    private function applySearchFilter($products)
    {
        if ($this->searchTerm) {
            return $products->whereHas('parent', function ($query) {
               $query->whereRaw("name LIKE \"%$this->searchTerm%\"")
                    ->orWhereRaw("code LIKE \"%$this->searchTerm%\"");
            });
        }

        return null;
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '15';
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function selectedDeparament($item)
    {
        if ($item)
            $this->departament = $item;
        else
            $this->departament = null;
    }

    public function savesuborder()
    {
        $this->validate();

        $model2 = Product::query()->onlySubProducts()->with('parent')->where('stock', '<>', 0)->paginate(10);

        $orderModel = Order::with('product_order')->find($this->order_id);

        foreach($model2 as $bal)
        {

            if(is_array($this->quantityy) && array_key_exists($bal->id, $this->quantityy)){

                $available = $bal->stock;

                $this->validate([
                    'quantityy.'.$bal->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$available,
                ]);
            }
        }

        if(!empty($this->quantityy)){

            // dd($this->quantityy);
            $suborder = new Order();
            $suborder->parent_order_id = $this->order_id;
            $suborder->departament_id = $this->departament ?? null;
            $suborder->date_entered = Carbon::now()->format('Y-m-d');
            $suborder->audi_id = Auth::id();
            $suborder->approved = true;
            $suborder->type = 4;
            $suborder->save();

            event(new OrderCreated($suborder));

            $departament = Departament::find($this->departament);

            foreach($this->quantityy as $key => $product){

                // dd($product['available']);
                if(!empty($product['available'])){

                    $SuborderIntoPro = $suborder;

                    $getProductOrder = ProductOrder::find($key)->product_id;

                    $getProduct = Product::with('parent')->withTrashed()->find($getProductOrder);

                    $SuborderIntoPro->product_suborder()->create([
                        'product_id' => $getProductOrder,
                        'quantity' => $product['available'],
                        'price' => $this->departament ? $getProduct->getPrice($departament->type_price ?? 'retail') : null,
                        'parent_product_id' => $key,
                    ]);
                }
            }
        }

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
        // $model2 = Product::query()->onlySubProducts()->with('parent')->where('stock', '<>', 0)->paginate(10);
        $model = Order::query()->onlySuborders()->outFromStore()->get();

        return view('backend.order.livewire.create-suborder', [
            'model2' => $this->rows,
            'model' => $model
        ]);
    }
}

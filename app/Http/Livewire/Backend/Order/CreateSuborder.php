<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Departament;
use App\Domains\Auth\Models\User;
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

    public $order_id, $quantityy, $departament, $user, $status_name;

    public $perPage = '30';

    public $searchTerm = '';

    public $sortField = 'updated_at';

    public $sortAsc = false;

    public ?string $date = null;

    public ?string $request = null;
    public ?string $purchase = null;
    public ?string $invoice = null;
    public ?string $comment = null;

    public $getSum = 0;

    public $nameDepa = null;
    public $namePrice = null;

    protected $listeners = ['selectedDeparament', 'selectedCompanyItem', 'savesuborder' => '$refresh', 'renderview' => 'render'];

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    // protected $rules = [
    //     'user' => 'prohibited_unless:departament,null',
    //     'departament' => 'prohibited_unless:user,null',
    // ];

    protected $rules = [
        'user' => [
            'required_without:departament',
            'prohibited_unless:departament,null',
        ],
        'departament' => [
            'required_without:user',
            'prohibited_unless:user,null',
        ],
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
            return $this->rowsQuery->get()->take($this->perPage)->sortBy('full_name_clear_sort');
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
        $this->perPage = '30';
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedQuantityy()
    {
        $sum = 0;

        foreach($this->quantityy as $key => $product){
            $sum += (int) $product['available'];
        }

        $this->getSum = $sum;
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function selectedDeparament($item)
    {
        if ($item)
            $this->departament = $item;

            if($this->departament != null){
                $depa = Departament::find($this->departament);
                $this->nameDepa = $depa->name ?? '';
                $this->namePrice = $depa->type_price_label ?? '';
            }

        else
            $this->departament = null;
    }

    public function selectedCompanyItem($item)
    {
        if ($item)
            $this->user = $item;

            if($this->user != null){
                $user = User::find($this->user);
                $this->nameDepa = $user->name ?? '';
                $this->namePrice = $user->type_price_label ?? '';
            }

        else
            $this->user = null;
    }

    public function clearFilterDepartament()
    {
        $this->emit('clear-departament');
        $this->departament = null;
    }

    public function clearFilterUser()
    {
        $this->emit('clear-user');
        $this->user = null;
    }

    public function savesuborder()
    {
        $this->validate();

        // dd('si');

        $suborder = Product::query()->onlySubProducts()->with('parent')->where('stock', '<>', 0)->get();

        foreach($suborder as $productGet)
        {
            // dd($productGet->stock);

            if(is_array($this->quantityy) && array_key_exists($productGet->id, $this->quantityy)){

                $available = $productGet->stock;
                
                $this->validate([
                    'quantityy.'.$productGet->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$available,
                ]);
            }
        }

        if(!empty($this->quantityy)){

            // dd($this->quantity);
            $suborder = new Order();
            $suborder->departament_id = $this->departament ?? null;
            $suborder->user_id = $this->user ?? null;
            $suborder->date_entered = Carbon::now()->format('Y-m-d');
            $suborder->audi_id = Auth::id();
            $suborder->approved = true;
            $suborder->type = 4;
            $suborder->date_entered = $this->date ?? today();
            $suborder->request = $this->request ?? null;
            $suborder->purchase = $this->purchase ?? null;
            $suborder->invoice = $this->invoice ?? null;
            $suborder->comment = $this->comment ?? null;
            $suborder->save();

            event(new OrderCreated($suborder));

            $departament = Departament::find($this->departament);
            $user = User::with('customer')->find($this->user);


            if($departament){
                $price = $departament->type_price;
            }

            if($user){
                if($user->customer){
                    $price = $user->customer->type_price;
                }
                else{
                    $price = 'retail';
                }
            }

            foreach($this->quantityy as $key => $product){

                // dd($product['available']);
                if(!empty($product['available'])){

                    $suborderIntoPro = $suborder;

                    $getProduct = Product::with('parent')->withTrashed()->find($key);

                    $suborderIntoPro->product_suborder()->create([
                        'product_id' => $key,
                        'quantity' => $product['available'],
                        'price' => ($this->departament || $this->user) ? $getProduct->getPriceWithIva($price) : $item->getPriceWithIva(),
                        'parent_product_id' => $key,
                        'type' => 4,
                    ]);

                    if($getProduct->isProduct()){
                        $getProduct->history_subproduct()->create([
                            'product_id' => optional($getProduct->parent)->id ?? null,
                            'stock' => $product['available'],
                            'old_stock' => $getProduct->stock ?? null,
                            'type_stock' => 'stock',
                            'price' => ($this->departament || $this->user) ? $getProduct->getPriceWithIva($price) : $item->getPriceWithIva(),
                            'order_id' => $suborderIntoPro->id,
                            'is_output' => true,
                            'audi_id' => Auth::id(),
                        ]);
                    }
                }
            }
        }

       $this->resetInput();
       
       $this->emit('renderview');

       $this->emit('clear-departament');
       $this->departament = null;

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
    }

    public function resetInput()
    {
        unset($this->quantityy);
    }

    public function render()
    {
        // $products = Product::query()->onlySubProducts()->with('parent')->where('stock', '<>', 0)->paginate(10);
        $model = Order::query()->with('departament', 'user', 'product_suborder')->onlySuborders()->orderBy('created_at', 'desc')->limit('10')->get();

        return view('backend.order.livewire.create-suborder', [
            'products' => $this->rows,
            'model' => $model
        ]);
    }
}

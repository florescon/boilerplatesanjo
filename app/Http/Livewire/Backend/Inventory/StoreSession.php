<?php

namespace App\Http\Livewire\Backend\Inventory;

use Livewire\Component;
use App\Models\Product;
use App\Models\Session;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class StoreSession extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    public $input = [];

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    public $perPage = '12';

    public $status;
    public $searchTerm = '';

    protected $listeners = ['postAdded' => 'addInventory', 'updatedListener' => 'render'];

    public function addInventory($code)
    {
        $product = Product::whereCode($code)->first();

        if($product){

            if($product->isChildren()){

                $last_record = DB::table('sessions')->orderByDesc('updated_at')->first();

                $last_record_product = isset($last_record) ? $last_record->product_id : null;

                if($last_record_product !== $product->id){
                    if(Session::where('product_id', $product->id)->where('audi_id', Auth::id())->exists()){

                        $productExist = DB::table('sessions')->where('product_id', $product->id)->first();

                        DB::table('sessions')->where('product_id', $product->id)->update(['capture' => $productExist->capture + 1, 'updated_at' => now()]);

                        $this->emit('swal:alert', [
                           'icon' => 'success',
                            'title'   => __('Se sumó'), 
                        ]);
                    }
                    else{

                        DB::table('sessions')->insert([
                            'product_id' => $product->id,
                            'capture' => 1,
                            'stock' => $product->stock_store,
                            'audi_id' => Auth::id(),
                            'type' => 'store',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $this->emit('swal:alert', [
                           'icon' => 'success',
                            'title'   => __('Se insertó producto'), 
                        ]);
                    }
                }
            }
            else{
                $this->emit('swal:alert', [
                    'icon' => 'warning',
                    'title'   => __('Es un producto padre, no se puede escanear :('), 
                ]);
            }
        }
        else{
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title'   => __('Verifica lo que estás escaneando'), 
            ]);
        }
    }

    public function getRowsQueryProperty()
    {
        $query = Session::query()
            ->with('product.parent', 'audi')
            ->where('audi_id', Auth::id())
            ->whereType('store')
            ->orderByDesc('updated_at');

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
            return $products->whereHas('product.parent', function ($query) {
               $query->whereRaw("name LIKE \"%$this->searchTerm%\"")
                    ->orWhereRaw("code LIKE \"%$this->searchTerm%\"");
            })
            ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('capture', 'like', '%' . $this->searchTerm . '%');
        }

        return null;
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '12';
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function increase($productIndex)
    {
        $this->validate([
            'input.*.save' => 'numeric|integer|not_in:0|sometimes',
        ]);

        $product = $this->input[$productIndex] ?? null;

        if(!is_null($product)){
            if(!empty($product['save'])){
                if($product['save'] > 0){
                    optional(Session::find($productIndex))->increment('capture', abs($product['save']));
                }
                else{
                    optional(Session::find($productIndex))->decrement('capture', abs($product['save']));
                }
            }
        }

        $this->emit('clearAll');
        $this->clearAll();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Amount changed'), 
        ]);
    }

    public function clearAll()
    {
        $this->input = [];
    }

    public function destroy($id)
    {
       Session::find($id)->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        // $session = Session::with('product', 'audi')->where('audi_id', Auth::id())->where('type', 'store')->orderByDesc('updated_at')->paginate(15);
        return view('backend.inventories.table.store-session', [
            'session' => $this->rows,
        ]);
    }
}

<?php

namespace App\Http\Livewire\Backend\Inventory;

use Livewire\Component;
use App\Models\Product;
use App\Models\Session;
use App\Models\Inventory;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class StockSession extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    public $input = [];

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];

    public $perPage = '15';

    public $status;
    public $searchTerm = '';

    public $sortField = 'updated_at';

    public $sortAsc = false;

    protected $listeners = ['postAdded' => 'addInventory', 'productCaptured' => 'whenProductCaptured', 'updatedListener' => 'render'];

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

                        DB::table('sessions')->where('product_id', $product->id)->update(['capture' => $productExist->capture + 1, 'stock' => $product->stock, 'updated_at' => now()]);

                        $this->emit('swal:alert', [
                           'icon' => 'success',
                            'title'   => __('Se sumó'), 
                        ]);
                    }
                    else{

                        DB::table('sessions')->insert([
                            'product_id' => $product->id,
                            'capture' => 1,
                            'stock' => $product->stock,
                            'audi_id' => Auth::id(),
                            'type' => 'stock',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $this->emit('swal:alert', [
                           'icon' => 'success',
                            'title'   => __('Se insertó producto'), 
                        ]);
                    }

                    $this->emit('productCaptured');

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

    public function whenProductCaptured()
    {
       $this->emit('playAudio');
    }

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
        $query = Session::query()
            ->with('product.parent', 'product.color', 'product.size', 'audi')
            ->where('audi_id', Auth::id())
            ->whereType('stock')
            ->whereNull('inventory_id')
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

    public function increase($productIndex)
    {
        $this->validate([
            'input.*.save' => 'numeric|integer|not_in:0|sometimes',
        ]);

        $product = $this->input[$productIndex] ?? null;

        if(!is_null($product)){
            if(!empty($product['save'])){
                if($product['save'] > 0){
                    optional(Session::whereNull('inventory_id')->whereType('stock')->find($productIndex))->increment('capture', abs($product['save']));
                }
                else{
                    optional(Session::whereNull('inventory_id')->whereType('stock')->find($productIndex))->decrement('capture', abs($product['save']));
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

    public function checkout()
    {
        $last_inventory = Inventory::whereType('stock')->where('audi_id', Auth::id())->withTrashed()->latest('id')->first();

        if(isset($last_inventory) ? $last_inventory->trashed() : null){
            $forRestore = $last_inventory;

            if($forRestore){
                $last_inventory->restore();
                $inventory = $last_inventory;
            }
        }
        else{
            $inventory = new Inventory();
            $inventory->type = 'stock';
            $inventory->audi_id = Auth::id();
            $inventory->save();
        }

        // $sessions = Session::query()->whereNull('inventory_id')->whereType('stock')->where('audi_id', Auth::id())->get();

        // $sessions = DB::table('sessions')->whereNull('inventory_id')->whereType('stock')->where('audi_id', Auth::id())->get();

        $sessions = Session::where('inventory_id', null)->where('type', 'stock')->where('audi_id', Auth::id())->orderBy('created_at')->chunk(100, function ($sessions) use ($inventory){
                foreach($sessions as $session){
                    $productExist = DB::table('products')->where('id', $session->product_id)->first();
                    DB::table('sessions')->where('product_id', $session->product_id)->update(['stock' => $productExist->stock]);
                }
        });

        $subProducts = Product::query()->with('session')->onlySubProducts()->with('parent')->where('stock', '<>', 0)->get();

        foreach($subProducts as $sub){

            if($sub->session()->doesntExist()){

                DB::table('product_inventories')->insert([
                    'product_id' => $sub->id,
                    'capture' => 0,
                    'stock' => $sub->stock,
                    'audi_id' => Auth::id(),
                    'type' => 'stock',
                    'inventory_id' => $inventory->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $sessionsUpdated = Session::query()->whereType('stock')->where('audi_id', Auth::id())->get();

        foreach($sessionsUpdated as $updated){

            DB::table('product_inventories')->insert([
                'product_id' => $updated->product_id,
                'capture' => $updated->capture,
                'stock' => $updated->stock,
                'audi_id' => Auth::id(),
                'type' => 'stock',
                'inventory_id' => $inventory->id,
                'created_at' => $updated->created_at,
                'updated_at' => $updated->updated_at,
            ]);
        }

        $this->clearAllSession();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);

        $this->emit('updatedListener');

        // return redirect()->route('admin.inventory.stock');
    }

    public function clearAll()
    {
        $this->input = [];
    }

    public function destroy($id)
    {
       Session::find($id)->forceDelete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function clearAllSession()
    {
        DB::table('sessions')->where('audi_id', Auth::id())->delete();
    }

    public function render()
    {
        // $session = Session::with('product', 'audi')->where('audi_id', Auth::id())->where('type', 'stock')->orderByDesc('updated_at')->paginate(15);
        return view('backend.inventories.table.stock-session', [
            'countProductsStock' => Product::query()->onlySubProducts()->with('parent')->where('stock', '<>', 0)->count(),
            'countRows' => Session::where('audi_id', Auth::id())->whereType('stock')->whereNull('inventory_id'),
            'inventories' => Inventory::query()->whereType('stock'),
            'session' => $this->rows,
        ]);
    }
}

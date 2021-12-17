<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;

class MoveStockProduct extends Component
{
    public $product_id, $product_name, $moveToStock, $moveToRevisionStock, $moveToStoreStock;

    public $inputmove, $inputmovefromRevision, $inputmovefromStore;

    protected $queryString = [
        'moveToStock' => ['except' => FALSE],
        'moveToRevisionStock' => ['except' => FALSE],
        'moveToStoreStock' => ['except' => FALSE],
    ];

    protected $validationAttributes = [
        'inputmove' => '',
        'inputmovefromRevision' => '',
        'inputmovefromStore' => '',
    ];

    protected $listeners = ['moveToStore', 'moveToStock', 'moveToRevision', 'clearAll' => '$refresh'];

    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->product_name = $product->name;
    }

    public function moveToStore(int $productId)
    {
        // dd($this->inputmovefromRevision);
        $product_to_move = Product::where('id', $productId)->first();

        // dd($product_to_move->stock);
        if($this->inputmove){
            $this->validate([
                'inputmove' => "sometimes|nullable|numeric|min:1|max:$product_to_move->stock",
            ]);
        }
        if($this->inputmovefromRevision){
            $this->validate([
                'inputmovefromRevision' => "sometimes|nullable|numeric|min:1|max:$product_to_move->stock_revision",            
            ]);
        }

        if($this->inputmove){
            $product_to_move->decrement('stock', abs($this->inputmove));
            $product_to_move->increment('stock_store', abs($this->inputmove));
        }

        if($this->inputmovefromRevision){
            $product_to_move->decrement('stock_revision', abs($this->inputmovefromRevision));
            $product_to_move->increment('stock_store', abs($this->inputmovefromRevision));
        }

        $this->emit('clearAll');
        $this->clearAll();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Amount moved'), 
        ]);
    }

    public function moveToRevision(int $productId)
    {
        // dd($this->inputmovefromRevision);
        $product_to_move = Product::where('id', $productId)->first();

        // dd($product_to_move->stock);
        if($this->inputmove){
            $this->validate([
                'inputmove' => "sometimes|nullable|numeric|min:1|max:$product_to_move->stock",
            ]);
        }
        if($this->inputmovefromStore){
            $this->validate([
                'inputmovefromStore' => "sometimes|nullable|numeric|min:1|max:$product_to_move->stock_store",            
            ]);
        }

        if($this->inputmove){
            $product_to_move->decrement('stock', abs($this->inputmove));
            $product_to_move->increment('stock_revision', abs($this->inputmove));
        }

        if($this->inputmovefromStore){
            $product_to_move->decrement('stock_store', abs($this->inputmovefromStore));
            $product_to_move->increment('stock_revision', abs($this->inputmovefromStore));
        }

        $this->emit('clearAll');
        $this->clearAll();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Amount moved'), 
        ]);
    }

    public function moveToStock(int $productId)
    {
        // dd($this->inputmovefromRevision);
        $product_to_move = Product::where('id', $productId)->first();

        if($this->inputmovefromRevision){
            $this->validate([
                'inputmovefromRevision' => "sometimes|nullable|numeric|min:1|max:$product_to_move->stock_revision",            
            ]);
        }
        if($this->inputmovefromStore){
            $this->validate([
                'inputmovefromStore' => "sometimes|nullable|numeric|min:1|max:$product_to_move->stock_store",
            ]);
        }

        if($this->inputmovefromRevision){
            $product_to_move->decrement('stock_revision', abs($this->inputmovefromRevision));
            $product_to_move->increment('stock', abs($this->inputmovefromRevision));
        }

        if($this->inputmovefromStore){
            $product_to_move->decrement('stock_store', abs($this->inputmovefromStore));
            $product_to_move->increment('stock', abs($this->inputmovefromStore));
        }

        $this->emit('clearAll');
        $this->clearAll();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Amount moved'), 
        ]);
    }

    public function clearAll()
    {
        $this->inputmove = [];
        $this->inputmovefromRevision = [];
        $this->inputmovefromStore = [];
    }

    public function updatedMoveToStock()
    {
        $this->moveToRevisionStock = FALSE;
        $this->moveToStoreStock= FALSE;
    }

    public function updatedMoveToRevisionStock()
    {
        $this->moveToStock = FALSE;
        $this->moveToStoreStock= FALSE;
    }

    public function updatedMoveToStoreStock()
    {
        $this->moveToStock = FALSE;
        $this->moveToRevisionStock= FALSE;
    }

    public function render()
    {
        $model = Product::with('children.parent')->findOrFail($this->product_id);
        $parents = $model->children->toArray();

        // dd($parents);

        return view('backend.product.livewire.move-stock')->with(compact('model', 'parents'));
    }
}

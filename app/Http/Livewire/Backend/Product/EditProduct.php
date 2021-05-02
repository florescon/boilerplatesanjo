<?php

namespace App\Http\Livewire\Backend\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Facades\Cart;

class EditProduct extends Component
{

    use WithFileUploads;

    public $shortId, $isCode, $code, $isDescription, $origDescription, $newDescription, $increaseStock, $subtractStock, $increaseStockRevision, $subtractStockRevision, $increaseStockStore, $subtractStockStore, $inputincrease, $inputsubtract, $inputincreaserevision, $inputsubtractrevision, $inputincreasestore, $inputsubtractstore, $product_id, $color_id_select, $size_id_select, $photo, $imageName, $origPhoto, $colorss;

    public $colorsmultiple_id = [];
    public $sizesmultiple_id = [];
    public $filters = [];
    public $filtersz = [];

	protected $queryString = [
        'increaseStock' => ['except' => FALSE],
        'subtractStock' => ['except' => FALSE],
        'increaseStockRevision' => ['except' => FALSE],
        'subtractStockRevision' => ['except' => FALSE],
        'increaseStockStore' => ['except' => FALSE],
        'subtractStockStore' => ['except' => FALSE],

    ];

    protected $listeners = ['filterByColor' => 'filterByColor', 'filterBySize' => 'filterBySize', 'increase', 'savecolor', 'storemultiple', 'clearAll' => '$refresh'];

    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->shortId = $product->slug;
        $this->origPhoto = $product->file_name;
        $this->origDescription = $product->description;
        $this->isCode = $product->code;
        $this->init($product);
        $this->initcode($product);
    }

    public function addToCart(int $productId): void
    {
        Cart::add(Product::
            with(array('parent' => function($query) {
                $query->select('id', 'name');
            }))->get()
            ->find($productId));
        $this->emit('productAdded');
    }


    public function removePhoto()
    {
        $this->photo = '';
    }

    public function filterByColor($color)
    {

        if (in_array($color, $this->filters)) {
            $ix = array_search($color, $this->filters);
            unset($this->filters[$ix]);
        } else {
            $this->filters[] = $color;

            array_shift($this->filtersz);

            if(count($this->filters) >= 2){
                array_shift($this->filters);
            };
    
        }

    }


    public function filterBySize($size)
    {

        if (in_array($size, $this->filtersz)) {
            $ix = array_search($size, $this->filtersz);
            unset($this->filtersz[$ix]);
        } else {
            $this->filtersz[] = $size;

            array_shift($this->filters);

            if(count($this->filtersz) >= 2){
                array_shift($this->filtersz);
            };
    
        }

    }

    private function applyColorFilter($model)
    {
        if ($this->filters) {
            foreach ($this->filters as $filter) {     
                $model = Product::with(['children' => function($query) use ($filter){
                        $query->where('color_id', $filter);
                    }]
                );
            }
        }

        return null;
    }


    public function savedescription()
    {
        $product = Product::findOrFail($this->product_id);
        $newDescription = (string)Str::of($this->newDescription)->trim()->substr(0, 100); // trim whitespace & more than 100 characters
        $newDescription = $newDescription === $this->shortId ? null : $newDescription; // don't save it as product name it if it's identical to the short_id

        $product->description = $newDescription ?? null;
        $product->save();

        $this->init($product); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function savecode()
    {

        $this->validate([
            'code' => 'required|min:3|unique:products',
        ]);

        $product = Product::findOrFail($this->product_id);
        $newCode = (string)Str::of($this->code)->trim()->substr(0, 30); // trim whitespace & more than 100 characters
        $newCode = $newCode === $this->shortId ? null : $newCode; // don't save it as product name it if it's identical to the short_id

        $product->code = $newCode ?? null;
        $product->save();

        $this->initcode($product); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function savePhoto()
    {

        $this->validate([
            'photo' => 'image|max:4096', // 4MB Max
        ]);

        if($this->photo)
            $imageName = $this->photo->store("images",'public');
        
        $record = Product::find($this->product_id);
        $record->update([
            'file_name' => $this->photo ? $imageName : null,
        ]);

        $this->removePhoto();

        $product = Product::findOrFail($this->product_id);
        $this->initphoto($product);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved photo'), 
        ]);
    }


    public function storemultiple()
    {

        $product = Product::findOrFail($this->product_id);

        $this->validate([
            'colorsmultiple_id' => 'required',
            'sizesmultiple_id' => 'required',
        ]);

        foreach($this->colorsmultiple_id as $color){
            
            foreach($this->sizesmultiple_id as $size){        
                $product->children()->saveMany([
                    new Product([
                        'size_id' => $size,
                        'color_id' => $color,
                    ]),
                ]);
            }
        }

        return redirect()->route('admin.product.edit', $product->id);
    }



    public function savecolor()
    {

        $product = Product::with('children')->findOrFail($this->product_id);

        if($this->color_id_select){
            if(!$product->children->contains('color_id', $this->color_id_select))
            {
                foreach($product->children->unique('size_id') as $sizes){
                    $product->children()->saveMany([
                        new Product([
                            'size_id' => $sizes->size->id,
                            'color_id' => $this->color_id_select,
                        ]),
                    ]);
                }
                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => __('New color added'), 
                ]);
            }
            else{

                $this->emit('swal:alert', [
                    'icon' => 'warning',
                    'title'   => __('Color already exists'), 
                ]);

            }
        }

        // $this->initmodel($product); // re-initialize the component state with fresh data after saving
    }


    public function savesize()
    {

        $product = Product::with('children')->findOrFail($this->product_id);

        if($this->size_id_select){
            if(!$product->children->contains('size_id', $this->size_id_select))
            {
                foreach($product->children->unique('color_id') as $colors){
                    $product->children()->saveMany([
                        new Product([
                            'size_id' => $this->size_id_select,
                            'color_id' => $colors->color->id,
                        ]),
                    ]);
                }
                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => __('New size added'), 
                ]);
            }
            else{
                $this->emit('swal:alert', [
                    'icon' => 'warning',
                    'title'   => __('Size already exists'), 
                ]);
            }
        }

        // $this->initmodel($product); // re-initialize the component state with fresh data after saving
    }



    public function clearAll()
    {

        $this->inputincrease = [];
        $this->inputsubtract = [];

        $this->inputincreaserevision = [];
        $this->inputsubtractrevision = [];

        $this->inputincreasestore = [];
        $this->inputsubtractstore = [];

    	$this->increaseStock = FALSE;
    	$this->subtractStock = FALSE;
        $this->increaseStockRevision = FALSE;
        $this->subtractStockRevision = FALSE;
        $this->increaseStockStore = FALSE;
        $this->subtractStockStore = FALSE;

    }


    public function increase()
    {

        $this->validate([
            'inputincrease.*.stock' => 'numeric|sometimes',
            'inputsubtract.*.stock' => 'numeric|sometimes',
            'inputincreaserevision.*.stock' => 'numeric|sometimes',
            'inputsubtractrevision.*.stock' => 'numeric|sometimes',
            'inputincreasestore.*.stock' => 'numeric|sometimes',
            'inputsubtractstore.*.stock' => 'numeric|sometimes',

        ]);

        // dd($this->inputincreaserevision);

        if($this->inputincrease){
    		foreach($this->inputincrease as $key => $productos){
    			if(!empty($productos['stock']))
    			{
		            $product_increment = Product::where('id', $key)->first();
		            $product_increment->increment('stock', abs($productos['stock']));
    			}
    		}
    	}

        if($this->inputsubtract){
    		foreach($this->inputsubtract as $key => $productos){
    			if(!empty($productos['stock']))
    			{
		            $product_increment = Product::where('id', $key)->first();
		            $product_increment->decrement('stock', abs($productos['stock']));
    			}
    		}
    	}

        if($this->inputincreaserevision){

            foreach($this->inputincreaserevision as $key => $productos){
                if(!empty($productos['stock']))
                {
                    $product_increment = Product::where('id', $key)->first();
                    $product_increment->increment('stock_revision', abs($productos['stock']));
                }
            }
        }

        if($this->inputsubtractrevision){
            foreach($this->inputsubtractrevision as $key => $productos){
                if(!empty($productos['stock']))
                {
                    $product_increment = Product::where('id', $key)->first();
                    $product_increment->decrement('stock_revision', abs($productos['stock']));
                }
            }
        }

        if($this->inputincreasestore){
            foreach($this->inputincreasestore as $key => $productos){
                if(!empty($productos['stock']))
                {
                    $product_increment = Product::where('id', $key)->first();
                    $product_increment->increment('stock_store', abs($productos['stock']));
                }
            }
        }

        if($this->inputsubtractstore){
            foreach($this->inputsubtractstore as $key => $productos){
                if(!empty($productos['stock']))
                {
                    $product_increment = Product::where('id', $key)->first();
                    $product_increment->decrement('stock_store', abs($productos['stock']));
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



    public function updatedIncreaseStock()
    {
        $this->subtractStock = FALSE;
        // $this->increaseStockRevision = FALSE;
        $this->subtractStockRevision= FALSE;
        $this->subtractStockStore= FALSE;
    }


    public function updatedSubtractStock()
    {
        $this->increaseStock = FALSE;
        $this->increaseStockRevision = FALSE;
        $this->increaseStockStore = FALSE;
    }

    public function updatedIncreaseStockRevision()
    {
        $this->subtractStock = FALSE;
        $this->subtractStockRevision = FALSE;
        $this->subtractStockStore = FALSE;
    }

    public function updatedSubtractStockRevision()
    {
        $this->increaseStock = FALSE;
        $this->increaseStockRevision = FALSE;
        $this->increaseStockStore = FALSE;
    }


    public function updatedIncreaseStockStore()
    {
        $this->subtractStock = FALSE;
        $this->subtractStockRevision = FALSE;
        $this->subtractStockStore = FALSE;
    }

    public function updatedSubtractStockStore()
    {
        $this->increaseStock = FALSE;
        $this->increaseStockRevision = FALSE;
        $this->increaseStockStore = FALSE;
    }


    private function init(Product $product)
    {
        $this->origDescription = $product->description ?: $this->shortId;
        $this->newDescription = $this->origDescription;
        $this->isDescription = $product->description ?? false;
    }

    private function initcode(Product $product)
    {
        $this->code = $product->code;
        $this->isCode = $product->code ?? false;
    }


    private function initphoto(Product $product)
    {
        $this->origPhoto = $product->file_name;
    }

    private function initmodel(Product $product)
    {

        // $attributes = Product::with('children')->findOrFail($this->product_id);
        $model = Product::with('children')
                        ->findOrFail($product->id);
    }


    public function render()
    {

        if ($this->filters || $this->filtersz) {
            if($this->filters){
                foreach ($this->filters as $filter) {     
                    $model = Product::with(['children' => function($query) use ($filter){
                            $query->where('color_id', $filter);
                        }]
                    );
                }
            }
            else{
                foreach ($this->filtersz as $filter) {     
                    $model = Product::with(['children' => function($query) use ($filter){
                            $query->where('size_id', $filter);
                        }]
                    );
                }
            }
        }
        else{
            $model = Product::with('children');
        }

        // $model = Product::with('children');
        // $this->applyColorFilter($model);


        $model = $model
                ->findOrFail($this->product_id);

        $attributes = Product::with('children')->findOrFail($this->product_id);


        return view('backend.product.livewire.edit')->with(compact('model', 'attributes'));
    }

}

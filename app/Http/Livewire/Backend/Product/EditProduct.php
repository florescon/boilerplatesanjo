<?php
namespace App\Http\Livewire\Backend\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Facades\Cart;
use Illuminate\Validation\Rule;
use App\Events\Product\ProductNameChanged;
use App\Events\Product\ProductCodeChanged;
use App\Events\Product\ProductDescriptionChanged;
use App\Events\Product\ProductLineChanged;
use App\Events\Product\ProductBrandChanged;
use App\Events\Product\ProductModelChanged;
use App\Events\Product\ProductColorCreated;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use DB;
use App\Domains\Auth\Models\User;

class EditProduct extends Component
{
    use WithFileUploads;

    public $slug, $isCode, $code, $isName, $name, $isPriceMaking, $price_making, $isCost, $cost, $isDescription, $origDescription, $newDescription, $inputformat, $inputincrease, $inputsubtract, $inputincreaserevision, $inputsubtractrevision, $inputincreasestore, $inputsubtractstore, $product_id, $color_id_select, $size_id_select, $photo, $imageName, $origPhoto;

    public ?int $line_id = null;
    public ?int $brand_id = null;
    public ?int $vendor_id = null;
    public ?int $model_product = null;

    public bool $increaseStock = false;
    public bool $subtractStock = false;
    public bool $increaseStockRevision = false;
    public bool $subtractStockRevision = false;
    public bool $increaseStockStore = false;
    public bool $subtractStockStore = false;

    public bool $showCodes = false;
    public bool $showLabels = false;
    public bool $showKardex = false;
    public bool $showSpecificConsumptions = false;

    public $colorsmultiple_id = [];
    public $sizesmultiple_id = [];
    public $filters = [];
    public $filtersz = [];

    public $nameStock;

	protected $queryString = [
        'increaseStock' => ['except' => FALSE],
        'subtractStock' => ['except' => FALSE],
        'increaseStockRevision' => ['except' => FALSE],
        'subtractStockRevision' => ['except' => FALSE],
        'increaseStockStore' => ['except' => FALSE],
        'subtractStockStore' => ['except' => FALSE],
        'showCodes' => ['except' => FALSE],
        'showLabels' => ['except' => FALSE],
        'showKardex' => ['except' => FALSE],
        'showSpecificConsumptions' => ['except' => FALSE],
    ];

    protected $messages = [
        'inputformat.*.*.not_in' => 'No se permiten ceros',
        'inputformat.*.*.regex' => 'Valor no permitido',
        'inputformat.*.*.numeric' => 'Debe ser un número',
        'inputformat.*.*.min' => 'Debe ser un número mayor a 1',
        'inputformat.*.*.max' => 'Debe ser un número menor a 10,000',
    ];

    protected $listeners = ['filterByColor' => 'filterByColor', 'filterBySize' => 'filterBySize', 'increase', 'savecolor', 'storemultiple', 'clearAll' => '$refresh'];

    public function mount(Product $product, string $nameStock = null)
    {
        $this->product_id = $product->id;
        $this->slug = $product->slug;
        $this->origPhoto = $product->file_name;
        $this->origDescription = $product->description;
        $this->isCode = $product->code;
        $this->init($product);
        $this->initcode($product);
        $this->initname($product);
        $this->initpricemaking($product);
        $this->initcost($product);
        $this->nameStock = $nameStock;
    }

    public function addToCart(int $productId, string $typeCart, ?int $amount = 1): void
    {
        Cart::add(Product::whereId($productId)->with('size', 'color')->
            with(array('parent' => function($query) {
                $query->select('id', 'slug', 'name', 'code', 'type', 'price', 'average_wholesale_price', 'wholesale_price', 'special_price', 'file_name');
            }))->first(), $typeCart, $amount);

        if($typeCart == 'products'){
            $this->emit('productAdded');
        }
        elseif($typeCart == 'products_sale'){
            $this->emit('productAddedSale');
        }
    }

    public function format()
    {
        $this->validate([
            'inputformat.*.*' => 'numeric|not_in:0|min:1|max:100000|sometimes',
        ]);

        // dd($this->inputformat);


        if($this->inputformat){

            foreach($this->inputformat as $color => $productos){


                while($array = current($productos)){

                    $size = key($productos);

                    // dd($color);

                    $quantity = $productos[$size];

                    $product = Product::where('parent_id', $this->product_id)->where('size_id', $size)->where('color_id', $color)->first()->withoutRelations();

                    // $this->addToCart($product->id, 'products', $quantity);

                    $productDB = DB::table('products')->where('name', 'John')->first();

                    DB::table('carts')->insert([
                        'product_id' => $product->id,
                        'price' => $product->getPriceWithIva(User::PRICE_RETAIL),
                        'quantity' => $quantity,
                        'type'=> 'quotation',
                        'branch_id' => 1,
                        'user_id' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    next($productos);
                }

                // foreach($productos as $size => $quantity){

                //     if($quantity != null)
                //     {
                //         $product = Product::where('parent_id', $this->product_id)->where('size_id', $size)->where('color_id', $color)->first()->withoutRelations();


                //         // $this->addToCart($product->id, 'products', $quantity);
                //     }
                // }
            }
        }

        $this->emit('clearAll');
        $this->clearAll();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
    }

    public function removePhoto()
    {
        $this->photo = '';
    }

    public function saveLine()
    {
        $productUpdated = Product::find($this->product_id);
        $productUpdated->update([
            'line_id' => $this->line_id,
        ]);

        event(new ProductLineChanged($productUpdated));

        return $this->redirectHere();
    }

    public function saveBrand()
    {
        $productUpdated = Product::find($this->product_id);
        $productUpdated->update([
            'brand_id' => $this->brand_id,
        ]);

        event(new ProductBrandChanged($productUpdated));

        return $this->redirectHere();
    }

    public function saveVendor()
    {
        $productUpdated = Product::find($this->product_id);
        $productUpdated->update([
            'vendor_id' => $this->vendor_id,
        ]);

        // event(new ProductBrandChanged($productUpdated));

        return $this->redirectHere();
    }

    public function saveModel()
    {
        $productUpdated = Product::find($this->product_id);
        $productUpdated->update([
            'model_product_id' => $this->model_product,
        ]);

        event(new ProductModelChanged($productUpdated));

        return $this->redirectHere();
    }

    public function activateProduct()
    {
        Product::whereId($this->product_id)->update(['status' => true]);
        return $this->redirectHere();
    }

    public function desactivateProduct()
    {
        Product::whereId($this->product_id)->update(['status' => false]);
        return $this->redirectHere();
    }

    public function activateCodesProduct()
    {
        Product::whereId($this->product_id)->update(['automatic_code' => true]);
        return $this->redirectHere();
    }

    public function desactivateCodesProduct()
    {
        Product::whereId($this->product_id)->update(['automatic_code' => false]);
        return $this->redirectHere();
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
        // foreach (Product::all() as $producte) {
        //     // dd($product->id);

        //     $eprod = Product::withTrashed()->find($producte->id);
        //     $eprod->updated_at = '2021-06-09 10:37:29';
        //     $eprod->save();

        // }

        $product = Product::findOrFail($this->product_id);
        $newDescription = (string)Str::of($this->newDescription)->trim()->substr(0, 100); // trim whitespace & more than 100 characters
        $newDescription = $newDescription === $this->slug ? null : $newDescription; // don't save it as product name it if it's identical to the short_id

        $product->description = $newDescription ?? null;
        $product->save();

        event(new ProductDescriptionChanged($product));

        $this->init($product); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function savename()
    {
        $this->validate([
            'name' => 'required|min:3|max:50',
        ]);

        $product = Product::findOrFail($this->product_id);
        $newName = (string)Str::of($this->name)->trim()->substr(0, 100); // trim whitespace & more than 100 characters

        $product->name = $newName ?? null;
        $product->save();

        $this->initname($product); // re-initialize the component state with fresh data after saving

        event(new ProductNameChanged($product));

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function savepricemaking()
    {
        $this->validate([
            'price_making' => 'nullable|not_in:0|regex:/^\d{1,13}(\.\d{1,4})?$/',
        ]);

        $product = Product::findOrFail($this->product_id);

        $product->price_making = $this->price_making ?? null;
        $product->save();

        $this->initpricemaking($product); // re-initialize the component state with fresh data after saving

        // event(new ProductNameChanged($product));

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function savecost()
    {
        $this->validate([
            'cost' => 'nullable|not_in:0|regex:/^\d{1,13}(\.\d{1,4})?$/',
        ]);

        $product = Product::findOrFail($this->product_id);

        $product->cost = $this->cost ?? null;
        $product->save();

        $this->initcost($product); // re-initialize the component state with fresh data after saving

        // event(new ProductNameChanged($product));

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function savecode()
    {
        $this->validate([
            'code' => ['required', 'min:3', 'max:20', 'regex:/^\S*$/u', Rule::unique('products')->ignore($this->product_id)],
        ]);

        $product = Product::findOrFail($this->product_id);
        $newCode = (string)Str::of($this->code)->trim()->substr(0, 30); // trim whitespace & more than 100 characters
        $newCode = $newCode === $this->slug ? null : $newCode; // don't save it as product name it if it's identical to the short_id

        $product->code = $newCode ?? null;
        $product->save();

        event(new ProductCodeChanged($product));

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

        $date = date("Y-m-d");

        if($this->photo)
            $imageName = $this->photo->store("pictures/".$date,'public');
        
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

        return $this->redirectHere();
    }

    public function savecolor()
    {
        $product = Product::with('childrenWithTrashed')->findOrFail($this->product_id);

        // event(new ProductColorCreated($product));

        if($this->color_id_select){

            $color = Color::findOrFail($this->color_id_select);

            if(!$product->childrenWithTrashed->contains('color_id', $this->color_id_select))
            {
                foreach($product->childrenWithTrashed->unique('size_id') as $sizes){
                    $product->childrenWithTrashed()->saveMany([
                        new Product([
                            'size_id' => $sizes->size->id,
                            'color_id' => $this->color_id_select,
                            'code' => $product->automatic_code ? ((optional($color)->short_name && optional($sizes->size)->short_name) ? $product->code.optional($color)->short_name.optional($sizes->size)->short_name : null) : null,
                        ]),
                    ]);
                }
                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => __('New color added'), 
                ]);
            }
            else{

                $product->childrenWithTrashed()
                    ->where('color_id', $this->color_id_select)
                    ->restore();

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
        $product = Product::with('childrenWithTrashed')->findOrFail($this->product_id);

        if($this->size_id_select){

            $size = Size::findOrFail($this->size_id_select);

            if(!$product->childrenWithTrashed->contains('size_id', $this->size_id_select))
            {
                foreach($product->childrenWithTrashed->unique('color_id') as $colors){
                    $product->childrenWithTrashed()->saveMany([
                        new Product([
                            'size_id' => $this->size_id_select,
                            'color_id' => $colors->color->id,
                            'code' => $product->automatic_code ? ((optional($colors->color)->short_name && optional($size)->short_name) ? $product->code.optional($colors->color)->short_name.optional($size)->short_name : null) : null,
                        ]),
                    ]);
                }
                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => __('New size added'), 
                ]);
            }
            else{

                $product->childrenWithTrashed()
                    ->where('size_id', $this->size_id_select)
                    ->restore();

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

        $this->inputformat = [];
    }

    public function clearCodeAndLabels()
    {
        $this->showCodes = FALSE;
        $this->showLabels = FALSE;
        $this->showKardex = FALSE;
        $this->showSpecificConsumptions = FALSE;
    }

    public function createHistory($product, $stock, bool $isOutput = false, string $typeStock)
    {
        $product->history_subproduct()->create([
            'product_id' => optional($product->parent)->id ?? null,
            'old_stock' => $product->$typeStock,
            'stock' => $stock,
            'type_stock' => $typeStock,
            'is_output' => $isOutput,
            'audi_id' => Auth::id(),
        ]);
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

                    $this->createHistory($product_increment, $productos['stock'], false, 'stock');

                    // $product_increment->history()->create([
                    //     'product_id' => $key,
                    //     'old_stock' => $product_increment->stock,
                    //     'stock' => $productos['stock'],
                    //     'type_stock' => 'stock',
                    //     'is_output' => false,
                    //     'audi_id' => Auth::id(),
                    // ]);

		            $product_increment->increment('stock', abs($productos['stock']));
    			}
    		}
    	}

        if($this->inputsubtract){
    		foreach($this->inputsubtract as $key => $productos){
    			if(!empty($productos['stock']))
    			{
		            $product_increment = Product::where('id', $key)->first();

                    $this->createHistory($product_increment, $productos['stock'], true, 'stock');

		            $product_increment->decrement('stock', abs($productos['stock']));
    			}
    		}
    	}

        if($this->inputincreaserevision){

            foreach($this->inputincreaserevision as $key => $productos){
                if(!empty($productos['stock']))
                {
                    $product_increment = Product::where('id', $key)->first();

                    $this->createHistory($product_increment, $productos['stock'], false, 'stock_revision');

                    $product_increment->increment('stock_revision', abs($productos['stock']));
                }
            }
        }

        if($this->inputsubtractrevision){
            foreach($this->inputsubtractrevision as $key => $productos){
                if(!empty($productos['stock']))
                {
                    $product_increment = Product::where('id', $key)->first();

                    $this->createHistory($product_increment, $productos['stock'], true, 'stock_revision');

                    $product_increment->decrement('stock_revision', abs($productos['stock']));
                }
            }
        }

        if($this->inputincreasestore){
            foreach($this->inputincreasestore as $key => $productos){
                if(!empty($productos['stock']))
                {
                    $product_increment = Product::where('id', $key)->first();

                    $this->createHistory($product_increment, $productos['stock'], false, 'stock_store');

                    $product_increment->increment('stock_store', abs($productos['stock']));
                }
            }
        }

        if($this->inputsubtractstore){
            foreach($this->inputsubtractstore as $key => $productos){
                if(!empty($productos['stock']))
                {
                    $product_increment = Product::where('id', $key)->first();

                    $this->createHistory($product_increment, $productos['stock'], true, 'stock_store');

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

    public function updatedShowCodes()
    {
        $this->showSpecificConsumptions = FALSE;
        $this->clearAll();
    }

    public function updatedShowLabels()
    {
        $this->showSpecificConsumptions = FALSE;
        $this->clearAll();
    }

    public function updatedShowKardex()
    {
        $this->showSpecificConsumptions = FALSE;
        $this->clearAll();
    }

    public function updatedShowSpecificConsumptions()
    {
        $this->showCodes = FALSE;
        $this->showLabels = FALSE;
        $this->showKardex = FALSE;
        $this->clearAll();
    }

    public function updatedIncreaseStock()
    {
        $this->subtractStock = FALSE;
        // $this->increaseStockRevision = FALSE;
        $this->subtractStockRevision= FALSE;
        $this->subtractStockStore= FALSE;
        $this->clearCodeAndLabels();
    }

    public function updatedSubtractStock()
    {
        $this->increaseStock = FALSE;
        $this->increaseStockRevision = FALSE;
        $this->increaseStockStore = FALSE;
        $this->clearCodeAndLabels();
    }

    public function updatedIncreaseStockRevision()
    {
        $this->subtractStock = FALSE;
        $this->subtractStockRevision = FALSE;
        $this->subtractStockStore = FALSE;
        $this->clearCodeAndLabels();
    }

    public function updatedSubtractStockRevision()
    {
        $this->increaseStock = FALSE;
        $this->increaseStockRevision = FALSE;
        $this->increaseStockStore = FALSE;
        $this->clearCodeAndLabels();
    }

    public function updatedIncreaseStockStore()
    {
        $this->subtractStock = FALSE;
        $this->subtractStockRevision = FALSE;
        $this->subtractStockStore = FALSE;
        $this->clearCodeAndLabels();
    }

    public function updatedSubtractStockStore()
    {
        $this->increaseStock = FALSE;
        $this->increaseStockRevision = FALSE;
        $this->increaseStockStore = FALSE;
        $this->clearCodeAndLabels();
    }

    private function init(Product $product)
    {
        $this->origDescription = $product->description ?: $this->slug;
        $this->newDescription = $this->origDescription;
        $this->isDescription = $product->description ?? false;
    }

    private function initcode(Product $product)
    {
        $this->code = $product->code;
        $this->isCode = $product->code ?? false;
    }

    private function initname(Product $product)
    {
        $this->name = $product->name;
        $this->isName = $product->name ?? false;
    }

    private function initpricemaking(Product $product)
    {
        $this->price_making = $product->price_making;
        $this->isPriceMaking = $product->price_making ?? false;
    }

    private function initcost(Product $product)
    {
        $this->cost = $product->cost;
        $this->isCost = $product->cost ?? false;
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

    public function redirectHere()
    {
        return $this->redirectRoute(!$this->nameStock ? 'admin.product.edit' : 'admin.store.product.edit', $this->product_id);
    }

    public function render()
    {
        if ($this->filters || $this->filtersz) {
            if($this->filters){
                foreach ($this->filters as $filter) {     
                    $model = Product::with(['children.parent', 'children' => function($query) use ($filter){
                            $query->where('color_id', $filter);
                        }]
                    );
                }
            }
            else{
                foreach ($this->filtersz as $filter) {     
                    $model = Product::with(['children.parent', 'children' => function($query) use ($filter){
                            $query->where('size_id', $filter);
                        }]
                    );
                }
            }
        }
        else{
            $model = Product::with('children.parent', 'line');
        }

        // $model = Product::with('children');
        // $this->applyColorFilter($model);

        $model = $model
                ->findOrFail($this->product_id);

        $attributes = Product::with('children')->findOrFail($this->product_id);

        return view('backend.product.livewire.edit')->with(compact('model', 'attributes'));


    }
}

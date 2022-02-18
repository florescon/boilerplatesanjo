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

class EditProduct extends Component
{
    use WithFileUploads;

    public $slug, $isCode, $code, $isName, $name, $isDescription, $origDescription, $newDescription, $inputincrease, $inputsubtract, $inputincreaserevision, $inputsubtractrevision, $inputincreasestore, $inputsubtractstore, $product_id, $color_id_select, $size_id_select, $photo, $imageName, $origPhoto;

    public ?int $line_id = null;
    public ?int $brand_id = null;
    public ?int $model_product = null;

    public bool $increaseStock = false;
    public bool $subtractStock = false;
    public bool $increaseStockRevision = false;
    public bool $subtractStockRevision = false;
    public bool $increaseStockStore = false;
    public bool $subtractStockStore = false;

    public bool $showCodes = false;
    public bool $showLabels = false;
    public bool $showSpecificConsumptions = false;

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
        'showCodes' => ['except' => FALSE],
        'showLabels' => ['except' => FALSE],
        'showSpecificConsumptions' => ['except' => FALSE],
    ];

    protected $listeners = ['filterByColor' => 'filterByColor', 'filterBySize' => 'filterBySize', 'increase', 'savecolor', 'storemultiple', 'clearAll' => '$refresh'];

    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->slug = $product->slug;
        $this->origPhoto = $product->file_name;
        $this->origDescription = $product->description;
        $this->isCode = $product->code;
        $this->init($product);
        $this->initcode($product);
        $this->initname($product);
    }

    public function addToCart(int $productId, string $typeCart): void
    {
        Cart::add(Product::
            with(array('parent' => function($query) {
                $query->select('id', 'slug', 'name', 'code', 'price', 'average_wholesale_price', 'wholesale_price', 'file_name');
            }))->get()
            ->find($productId), $typeCart);

        if($typeCart == 'products'){
            $this->emit('productAdded');
        }
        elseif($typeCart == 'products_sale'){
            $this->emit('productAddedSale');
        }
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

        return $this->redirectRoute('admin.product.edit', $this->product_id);
    }

    public function saveBrand()
    {
        $productUpdated = Product::find($this->product_id);
        $productUpdated->update([
            'brand_id' => $this->brand_id,
        ]);

        event(new ProductBrandChanged($productUpdated));

        return $this->redirectRoute('admin.product.edit', $this->product_id);
    }

    public function saveModel()
    {
        $productUpdated = Product::find($this->product_id);
        $productUpdated->update([
            'model_product_id' => $this->model_product,
        ]);

        event(new ProductModelChanged($productUpdated));

        return $this->redirectRoute('admin.product.edit', $this->product_id);
    }

    public function activateProduct()
    {
        Product::whereId($this->product_id)->update(['status' => true]);
        return $this->redirectRoute('admin.product.edit', $this->product_id);
    }

    public function desactivateProduct()
    {
        Product::whereId($this->product_id)->update(['status' => false]);
        return $this->redirectRoute('admin.product.edit', $this->product_id);
    }

    public function activateCodesProduct()
    {
        Product::whereId($this->product_id)->update(['automatic_code' => true]);
        return $this->redirectRoute('admin.product.edit', $this->product_id);
    }

    public function desactivateCodesProduct()
    {
        Product::whereId($this->product_id)->update(['automatic_code' => false]);
        return $this->redirectRoute('admin.product.edit', $this->product_id);
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

        return $this->redirectRoute('admin.product.edit', $product->id);
    }

    public function savecolor()
    {
        $product = Product::with('childrenWithTrashed')->findOrFail($this->product_id);

        // event(new ProductColorCreated($product));

        if($this->color_id_select){
            if(!$product->childrenWithTrashed->contains('color_id', $this->color_id_select))
            {
                foreach($product->childrenWithTrashed->unique('size_id') as $sizes){
                    $product->childrenWithTrashed()->saveMany([
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
            if(!$product->childrenWithTrashed->contains('size_id', $this->size_id_select))
            {
                foreach($product->childrenWithTrashed->unique('color_id') as $colors){
                    $product->childrenWithTrashed()->saveMany([
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
    }

    public function clearCodeAndLabels()
    {
        $this->showCodes = FALSE;
        $this->showLabels = FALSE;
        $this->showSpecificConsumptions = FALSE;
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

    public function updatedShowSpecificConsumptions()
    {
        $this->showCodes = FALSE;
        $this->showLabels = FALSE;
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
                    $model = Product::with(['children.parent.parent', 'children' => function($query) use ($filter){
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

<?php

namespace App\Http\Livewire\Frontend\Shop;

use App\Models\Frontend\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\Line;
use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;

class ShopComponent extends Component
{
	use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

	protected $queryString = [
        'searchTermShop' => ['except' => ''],
        'perPage',
        'brandName' => ['except' => ''],
    ];

    public $perPage = '12';

    public $status;

    public ?int $line = null;
    public ?int $brand = null;
    public ?int $color = null;
    public ?int $size = null;

    public string $brandName = '';
 
    public $sorting;
    public $searchTermShop = '';

    protected $listeners = ['selectedBrandItem', 'selectedColorItem', 'selectedSizeItem', 'restore' => '$refresh'];


    public function getRowsQueryProperty()
    {
        $query = Product::query()
            ->onlyProducts()
            ->with('children', 'line', 'brand')
            ->whereNull('parent_id')
            ->onlyActive();

        // $query->whereNull('parent_id');

        if($this->line){
            $line = $this->line;
            $query->whereHas('line', function($queryLine) use ($line){
                $queryLine->where('id', $line);
            });
        }

        if($this->brand){
            $brand = $this->brand;
            $query->whereHas('brand', function($queryBrand) use ($brand){
                $queryBrand->where('id', $brand);
            });
        }

        if($this->brandName){
            $brandN = $this->brandName;
            $query->whereHas('brand', function($queryBrand) use ($brandN){
                $queryBrand->where('slug', $brandN);
            });
        }
        if($this->size){
            $size = $this->size;
            $query->whereHas('children', function($querySize) use ($size){
                $querySize->where('size_id', $size);
            });
        }
        if($this->color){
            $color = $this->color;
            $query->whereHas('children', function($queryColor) use ($color){
                $queryColor->where('color_id', $color);
            });
        }

        if($this->sorting == 'newness'){
            return $query->newness();
        }
        else if($this->sorting == 'price'){
            return $query->priceAsc();
        }
        else if($this->sorting == 'price-desc'){
            return $query->priceDesc();
        }

        $this->applySearchFilter($query);

        return $query->defaultOrder();
    }

    private function applySearchFilter($products)
    {
        if ($this->searchTermShop) {
            return $products->whereRaw("code LIKE \"%$this->searchTermShop%\"")
                            ->orWhereRaw("name LIKE \"%$this->searchTermShop%\"")
                            ->orWhereRaw("description LIKE \"%$this->searchTermShop%\"")
                            ->onlyActive()
                            ->onlyProducts();
        }

        return null;
    }

    public function selectedBrandItem(?int $item)
    {
        if ($item) {
            $this->brand = $item;
        }
        else
            $this->brand = null;
    }
    public function selectedSizeItem(?int $item)
    {
        if ($item) {
            $this->size = $item;
        }
        else
            $this->size = null;
    }
    public function selectedColorItem(?int $item)
    {
        if ($item) {
            $this->color = $item;
        }
        else
            $this->color = null;
    }

    public function lineID(int $line)
    {
        $this->line = $line;
    }
    public function sizeID(int $size)
    {
        $this->size = $size;
    }
    public function colorID(int $color)
    {
        $this->color = $color;
    }

    public function clearFilterBrand()
    {
        $this->brand = null;
        $this->brandName = '';
    }
    public function clearFilterColor()
    {
        $this->color = null;
    }
    public function clearFilterSize()
    {
        $this->size = null;
    }
    public function clearFilters()
    {
        return redirect()->route('frontend.shop.index');
    }

    public function dehydrateBrand()
    {
        $this->brandName = '';
    }

    public function clear()
    {
        $this->searchTermShop = '';
        $this->resetPage();
        $this->perPage = '12';
    }

    public function updatedSearchTermShop()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function hydrateBrand()
    {
        // dd($this->line);
        // $lineModel = Line::find($this->line)->first();
        // $this->nameLine = $lineModel->name;
        $this->resetPage();
    }
    public function hydrateColor()
    {
        $this->resetPage();
    }
    public function hydrateSize()
    {
        $this->resetPage();
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    public function render()
    {
        $colors = Color::inRandomOrder()->limit(8)->get();
        $sizes = Size::inRandomOrder()->limit(5)->get();
        $lines = Line::inRandomOrder()->limit(6)->get();

		return view('frontend.shop.livewire.shop-component',[
            'products' => $this->rows,
            'colors' => $colors,
            'sizes' => $sizes,
            'lines' => $lines,
		]);
    }
}

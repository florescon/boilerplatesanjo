<?php

namespace App\Http\Livewire\Frontend\Shop;

use App\Models\Frontend\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\Line;
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
        'lineName' => ['except' => ''],
    ];

    public $perPage = '12';

    public $status;

    public ?int $line = null;
    public ?int $color = null;
    public ?int $size = null;

    public string $lineName = '';
 
    public $sorting;
    public $searchTermShop = '';

    protected $listeners = ['selectedLineItem', 'selectedColorItem', 'selectedSizeItem', 'restore' => '$refresh'];


    public function getRowsQueryProperty()
    {
        $query = Product::query()
            ->with('children', 'line')
            ->whereNull('parent_id')
            ->onlyActive();

        $this->applySearchFilter($query);

        // $query->whereNull('parent_id');

        if($this->line){
            $line = $this->line;
            $query->whereHas('line', function($queryLine) use ($line){
                $queryLine->where('id', $line);
            });
        }

        if($this->lineName){

            // $this->validate(['lineName' => 'required|max:6']);

            $lineN = $this->lineName;
            $query->whereHas('line', function($queryLine) use ($lineN){
                $queryLine->where('slug', $lineN);
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

        return $query->defaultOrder();
    }



    private function applySearchFilter($products)
    {
        if ($this->searchTermShop) {
            return $products->whereRaw("code LIKE \"%$this->searchTermShop%\"")
                            ->orWhereRaw("name LIKE \"%$this->searchTermShop%\"")
                            ->orWhereRaw("description LIKE \"%$this->searchTermShop%\"")
                            ->onlyActive();
        }

        return null;
    }

    public function selectedLineItem(?int $item)
    {
        if ($item) {
            $this->line = $item;
        }
        else
            $this->line = null;
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

    public function clearFilterLine()
    {
        $this->line = null;
        $this->lineName = '';

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

    public function dehydrateLine()
    {
        $this->lineName = '';
    }

    public function clear()
    {
        $this->searchTermShop = '';
        $this->page = 1;
        $this->perPage = '12';
    }


    public function updatedSearchTermShop()
    {
        $this->page = 1;
    }

    public function updatedPerPage()
    {
        $this->page = 1;
    }

    public function hydrateLine()
    {
        // dd($this->line);
        // $lineModel = Line::find($this->line)->first();
        // $this->nameLine = $lineModel->name;
        $this->page = 1;
    }
    public function hydrateColor()
    {
        $this->page = 1;
    }
    public function hydrateSize()
    {
        $this->page = 1;
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

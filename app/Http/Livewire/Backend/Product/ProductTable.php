<?php

namespace App\Http\Livewire\Backend\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use App\Events\Product\ProductRestored;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\ParentProductsExport;
use Excel;
use DB;

class ProductTable extends Component
{
	use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

	protected $queryString = [
        'searchTerm' => ['except' => ''],
        'searchTermExactly' => ['except' => ''],
        'brandName' => ['except' => ''],
        'perPage',
    ];

    public $perPage = '12';

    public $status;
    public $searchTerm = '';
    public $searchTermExactly = '';

    public bool $incomes = false;

    public ?int $brand = null;
    public ?int $color = null;

    public string $brandName = '';

    public $nameStock = null;
    public $linkEdit = null;
    
    protected $listeners = ['selectedBrandItem', 'selectedColorItem', 'restore' => '$refresh'];

    public function mount(string $nameStock = null, string $linkEdit = null)
    {
        $this->nameStock = $nameStock;
        $this->linkEdit = $linkEdit;
    }

    public function getRowsQueryProperty()
    {
        $query = Product::query()
            ->onlyProducts()
            ->with('children', 'consumption')
            ->withCount('children')
            ->whereNull('parent_id')
            ->orderBy('updated_at', 'desc');

        if($this->brand){
            $brand = $this->brand;
            $query->whereHas('brand', function($queryBrand) use ($brand){
                $queryBrand->where('id', $brand);
            });
        }
        if($this->color){
            $color = $this->color;
            $query->whereHas('children', function($queryColor) use ($color){
                $queryColor->where('color_id', $color);
            });
        }

        if($this->brandName){
            $brandN = $this->brandName;
            $query->whereHas('brand', function($queryBrand) use ($brandN){
                $queryBrand->where('slug', $brandN);
            });
        }


        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        if ($this->searchTermExactly) {
            $this->applySearchFilterExactly($query);
        }
        if($this->searchTerm){
            $this->applySearchFilter($query);
        }

        return $query;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    public function selectedBrandItem(?int $item)
    {
        if ($item) {
            $this->brand = $item;
        }
        else
            $this->brand = null;
    }

    public function selectedColorItem(?int $item)
    {
        if ($item) {
            $this->color = $item;
        }
        else
            $this->color = null;
    }

    public function colorID(int $color)
    {
        $this->color = $color;
    }


    public function hydrateColor()
    {
        $this->resetPage();
    }

    public function clearFilterColor()
    {
        // $this->color = null;
        $this->emit('clear-color');

    }

    public function hydrateBrand()
    {
        $this->resetPage();
    }

    public function clearFilterBrand()
    {
        // $this->brand = null;
        // $this->brandName = '';

        $this->emit('clear-brand');
    }

    private function applySearchFilter($products)
    {
        if ($this->searchTerm) {

            $terms = explode(' ',  $this->searchTerm);

            return $products->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    // $query->where('name', 'like', '%'.$searchTerm.'%')
                    // ->orWhere('lastname', 'like', '%'.$searchTerm.'%')

                    $query->whereRaw("name LIKE \"%$term%\"");
                }
            })->onlyProducts();
        }

        return null;
    }

    private function applySearchFilterExactly($products)
    {
        if ($this->searchTermExactly) {
            return $products->whereRaw("code LIKE \"%$this->searchTermExactly%\"")
                            ->onlyProducts();
        }

        return null;
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->searchTermExactly = '';
        $this->resetPage();
        $this->perPage = '12';
    }

    public function updatedSearchTerm()
    {
        $this->searchTermExactly = '';
        $this->resetPage();
    }

    public function updatedSearchTermExactly()
    {
        $this->searchTerm = '';
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    private function getSelectedProducts()
    {
        return Product::query()
            ->onlyProducts()->whereNull('parent_id')->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new ParentProductsExport($this->getSelectedProducts()), 'product-list-'.Carbon::now().'.'.$extension);
    }

    public function restore($id)
    {
        if($id){
            $restore_product = Product::withTrashed()
                ->where('id', $id)
                ->first();

            event(new ProductRestored($restore_product));

            $restore_product->restore();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }

    public function render()
    {
        return view('backend.product.table.product-table', [
            'products' => $this->rows,
        ]);
    }
}

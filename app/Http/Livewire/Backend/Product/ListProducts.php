<?php

namespace App\Http\Livewire\Backend\Product;

use App\Models\Product;
use App\Models\ProductHistory;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\ProductsExport;
use App\Exports\ProductMainExport;
use App\Exports\ProductRevisionExport;
use App\Exports\ProductStoreExport;
use Excel;
use DB;

class ListProducts extends Component
{
	use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    public $search;

    public $searchTerm = '';
    public $perPage = '15';
    public $dateInput = '';
    public $dateOutput = '';

    public $sortField = 'created_at';
    public $sortAsc = false;

	protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => '']
    ];

    private function applySearchFilter($products)
    {
        if ($this->searchTerm) {
            return $products->whereHas('parent', function ($query) {
     		   $query->whereRaw("name LIKE \"%$this->searchTerm%\"")
                    ->orWhereRaw("code LIKE \"%$this->searchTerm%\"");
    		})
            ->orWhere('code', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
            ->onlySubProducts();
        }

        return null;
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

    public function updateProductDates()
    {
        $NullDatesProducts = Product::with('parent')->whereNotNull('parent_id')->whereNull('created_at')->get();

        if($NullDatesProducts->count()){
            foreach($NullDatesProducts as $product){
                $parent = $product->parent;

                DB::table('products')->where('id', $product->id)->update(['created_at' => $parent->created_at, 'updated_at' => $parent->updated_at]);
            }
        }        
 
        return redirect()->route('admin.product.list');
    }

    public function getRowsQueryProperty()
    {
        $query = Product::query()->with('parent', 'color', 'size')
            ->whereHas('parent', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->onlySubProducts()
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                    $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', now()]) :
                    $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
            })
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

    public function clearFilterDate()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '15';
    }

    public function clearAll()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '15';
        $this->selectPage = false;
        $this->selectAll = false;
        $this->selected = [];
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedDateInput()
    {
        $this->resetPage();
    }

    public function export()
    {
        return response()->streamDownload(function () {
            echo $this->selectedRowsQuery->toCsv();
        }, 'color-list.csv');
    }

    private function getSelectedProducts()
    {
        return $this->selectedRowsQuery->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        // return $this->selectedRowsQuery->where('stock_store', '>', 0)->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }
    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new ProductsExport($this->getSelectedProducts()), 'product-list-'.Carbon::now().'.'.$extension);
    }

    private function getSelectedStoreProducts()
    {
        return $this->selectedRowsQuery->where('stock_store', '<>', 0)->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }
    private function getSelectedRevisionProducts()
    {
        return $this->selectedRowsQuery->where('stock_revision', '<>', 0)->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }
    private function getSelectedMainProducts()
    {
        return $this->selectedRowsQuery->where('stock', '<>', 0)->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    /**
     * Export only stock.
     *
     */
    public function exportMaatwebsiteCustom($extension, $stock)
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);

        if($stock == 'store'){
            return Excel::download(new ProductStoreExport($this->getSelectedStoreProducts()), 'product-store-list-'.Carbon::now().'.'.$extension);
        }
        if($stock == 'revision'){
            return Excel::download(new ProductRevisionExport($this->getSelectedRevisionProducts()), 'product-revision-list-'.Carbon::now().'.'.$extension);
        }
        if($stock == 'main'){
            return Excel::download(new ProductMainExport($this->getSelectedMainProducts()), 'product-main-list-'.Carbon::now().'.'.$extension);
        }
    }

    public function render()
    {
        $NullDatesProducts = Product::with('parent')->whereNotNull('parent_id')->whereNull('created_at')->get();

        $products = ProductHistory::query()->whereNull('subproduct_id')->get();

        foreach($products as $product){

            $prod = Product::find($product->product_id);

            $product->update([
                'product_id' => $prod->parent_id ?? null,
                'subproduct_id' => $prod->id,
            ]);
        }

        return view('backend.product.table.product-list', [
            'products' => $this->rows,
            'NullDatesProducts' => $NullDatesProducts,
        ]);
    }
}

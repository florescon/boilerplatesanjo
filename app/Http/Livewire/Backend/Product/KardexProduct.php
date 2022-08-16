<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\ProductKardexExport;
use Excel;
use DB;

class KardexProduct extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => ''],
        'perPage',
    ];

    public $perPage = '10';

    public $sortField = 'created_at';
    public $sortAsc = false;
    
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

    public $created, $updated, $selected_id, $deleted;

    public $myDate = [];

    public Product $product;

    public int $product_id;

    public int $product_parent;

    public $name;

    protected $listeners = ['rend' => 'render'];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->product_id =  $product->id;
        $this->product_parent =  $product->isChildren() ? optional($product->parent)->id : $product->id;
        $this->name = $product->full_name_clear ?? '';
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
        $product = Product::findOrFail($this->product_id);

        if($product->isChildren()){
            $query = $product->history_subproduct()->with('subproduct.parent', 'subproduct.size', 'subproduct.color')->orderBy('created_at', 'desc');
        }
        else{
            $query = $product->history()->with('subproduct.parent', 'subproduct.size', 'subproduct.color')->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    public function loadMore(?string $day = null): void
    {
        array_push($this->myDate, $day);
    }

    private function getSelectedProducts()
    {
        return $this->selectedRowsQuery->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    public function exportMaatwebsite($extension)
    {   
        // dd($this->getSelectedProducts());

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new ProductKardexExport($this->getSelectedProducts()), 'product-kardex-'.Carbon::now().'.'.$extension);
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
    }

    public function clearFilterDate()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
    }

    public function clearAll()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->searchTerm = '';
        $this->resetPage();
        $this->deleted = FALSE;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
        $this->myDate = [];
    }

    public function updatedSearchTerm()
    {
        $this->myDate = [];
        $this->resetPage();
    }

    public function updatedDateInput()
    {
        $this->myDate = [];
        $this->resetPage();
    }

    public function render()
    {
        return view('backend.product.livewire.kardex', [
            'history' => $this->rows,
        ]);
    }
}

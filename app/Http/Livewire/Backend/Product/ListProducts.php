<?php

namespace App\Http\Livewire\Backend\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;

class ListProducts extends Component
{

	use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    public $search;

    public $searchTerm = '';
    public $perPage = '12';

	protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
    ];


    private function applySearchFilter($products)
    {
        if ($this->searchTerm) {
            return $products->whereHas('parent', function ($query) {
     		   $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
    		});
        }

        return null;
    }


    public function getRowsQueryProperty()
    {
        
        $query = Product::query()
            ->with('parent', 'color', 'size')
            ->where('parent_id', '<>', NULL)->orderBy('updated_at', 'desc');

        $this->applySearchFilter($query);

        return $query;


    }


    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }


    public function clear()
    {
        $this->searchTerm = '';
        $this->page = 1;
        $this->perPage = '12';
    }

    public function updatedSearchTerm()
    {
        $this->page = 1;
    }


    public function updatedPerPage()
    {
        $this->page = 1;
    }

    public function render()
    {
        return view('backend.product.table.product-list', [
            'products' => $this->rows,
        ]);

    }
}

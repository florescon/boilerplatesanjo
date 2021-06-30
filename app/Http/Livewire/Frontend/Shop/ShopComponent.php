<?php

namespace App\Http\Livewire\Frontend\Shop;

use App\Models\Product;
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
    ];


    public $perPage = '12';

    public $status;
    public $searchTermShop = '';

    protected $listeners = ['restore' => '$refresh'];


    public function getRowsQueryProperty()
    {
        
        $query = Product::query()
            ->with('children', 'line')
            ->whereNull('parent_id')->orderBy('updated_at', 'desc');

        $this->applySearchFilter($query);


        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        return $query;


    }



    private function applySearchFilter($products)
    {
        if ($this->searchTermShop) {
            return $products->whereRaw("code LIKE \"%$this->searchTermShop%\"")
                            ->orWhereRaw("name LIKE \"%$this->searchTermShop%\"")
                            ->orWhereRaw("description LIKE \"%$this->searchTermShop%\"");
        }

        return null;
    }


    public function clear()
    {
        $this->searchTermShop = '';
        $this->page = 1;
        $this->perPage = '12';
    }


    public function updatedsearchTermShop()
    {
        $this->page = 1;
    }

    public function updatedPerPage()
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

		return view('frontend.shop.livewire.shop-component',[
            'products' => $this->rows,
		]);
    }
}

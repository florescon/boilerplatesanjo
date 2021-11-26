<?php

namespace App\Http\Livewire\Backend\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\ProductsExport;
use Excel;

class ListProducts extends Component
{
	use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    public $search;

    public $searchTerm = '';
    public $perPage = '12';
    public $dateInput = '';
    public $dateOutput = '';

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
     		   $query->whereRaw("name LIKE \"%$this->searchTerm%\"");
    		});
        }

        return null;
    }

    public function getRowsQueryProperty()
    {
        $query = Product::query()
            ->with('parent', 'color', 'size')
            ->whereHas('parent', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('parent_id', '<>', NULL)->orderBy('updated_at', 'desc')
            ->when($this->dateInput, function ($query) {
                empty($this->dateOutput) ?
                    $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', now()]) :
                    $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
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
        $this->page = 1;
        $this->perPage = '12';
    }

    public function clearAll()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
        $this->searchTerm = '';
        $this->page = 1;
        $this->perPage = '12';
        $this->selectPage = false;
        $this->selectAll = false;
        $this->selected = [];

    }

    public function updatedSearchTerm()
    {
        $this->page = 1;
    }

    public function updatedPerPage()
    {
        $this->page = 1;
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
    }
    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'pdf', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new ProductsExport($this->getSelectedProducts()), 'product-list-'.Carbon::now().'.'.$extension);
    }

    public function render()
    {
        return view('backend.product.table.product-list', [
            'products' => $this->rows,
        ]);

    }
}

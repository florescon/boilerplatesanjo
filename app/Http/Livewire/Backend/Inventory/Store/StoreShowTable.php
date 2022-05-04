<?php

namespace App\Http\Livewire\Backend\Inventory\Store;

use Livewire\Component;
use App\Models\Inventory;
use App\Models\ProductInventory;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\ProductInventoriesExport;
use Excel;

class StoreShowTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    public $perPage = '10';

    public $sortField = 'id';
    public $sortAsc = false;
    
    public $searchTerm = '';

    public int $inventory_id;

    public $updated, $selected_id, $deleted;

    public $status;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function mount(Inventory $inventory)
    {
        $this->inventory_id = $inventory->id;
    }

    public function getRowsQueryProperty()
    {
        $query = ProductInventory::query()->where('inventory_id', $this->inventory_id)->with('product.color', 'product.size', 'product.parent', 'audi')
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            });

        if ($this->status === 'deleted') {
            $this->applySearchDeletedFilter($query);

            return $query->onlyTrashed();
        }
        else{
            $this->applySearchFilter($query);
        }        

        return $query;
    }

    private function applySearchFilter($searchProduct)
    {
        if ($this->searchTerm) {
               return $searchProduct->where('inventory_id', $this->inventory_id)->with('product.color', 'product.size', 'product.parent', 'audi')->whereHas('product.parent', function ($query) {
               $query->whereRaw("name LIKE \"%$this->searchTerm%\"")
                    ->orWhereRaw("code LIKE \"%$this->searchTerm%\"");
            })
            ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('stock', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('capture', 'like', '%' . $this->searchTerm . '%');
     }

        return null;
    }

    private function applySearchDeletedFilter($searchProduct)
    {
        if ($this->searchTerm) {
            return $searchProduct->onlyTrashed()
                    ->whereRaw("id LIKE \"%$this->searchTerm%\"");
        }

        return null;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    public function isCurrentWeek()
    {
        $this->clearFilterDate();
        $this->currentMonth = FALSE;
        $this->today = FALSE;
        $this->currentWeek = TRUE;
    }

    public function isToday()
    {
        $this->clearFilterDate();
        $this->currentMonth = FALSE;
        $this->currentWeek = FALSE;
        $this->today = TRUE;
    }

    public function clearAll()
    {
        $this->clearFilterDate();
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '10';
        $this->deleted = FALSE;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->resetPage();
        $this->perPage = '10';
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedDeleted()
    {
        $this->resetPage();
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function export()
    {
        return response()->streamDownload(function () {
            echo $this->selectedRowsQuery->toCsv();
        }, 'store-show-table.csv');
    }

    private function getSelectedProducts()
    {
        return $this->selectedRowsQuery->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        // return $this->selectedRowsQuery->where('stock_store', '>', 0)->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }
    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new ProductInventoriesExport($this->getSelectedProducts()), 'product-store-inventory-'.Carbon::now().'.'.$extension);
    }


    public function render()
    {
        $inventory = Inventory::findOrFail($this->inventory_id);

        return view('backend.inventories.store.store-show-table',[
            'cashes' => $this->rows,
            'inventory' => $inventory,
        ]);
    }
}

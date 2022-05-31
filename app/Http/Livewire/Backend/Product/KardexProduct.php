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

class KardexProduct extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'dateInput' => ['except' => ''],
        'dateOutput' => ['except' => ''],
    ];

    public $sortField = 'created_at';
    public $sortAsc = false;
    
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

    public $created, $updated, $selected_id, $deleted;

    public $myDate = [];

    public Product $product;

    public int $product_id;

    public $name;

    protected $listeners = ['rend' => 'render'];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->product_id = $product->id;
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

    public function loadMore(?string $day = null): void
    {
        array_push($this->myDate, $day);
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

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery;
        });
    }

    public function render()
    {
        return view('backend.product.livewire.kardex');
    }
}

<?php

namespace App\Http\Livewire\Backend\Material;

use App\Models\MaterialHistory;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class MaterialRecordsHistoryGroup extends Component
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

    protected $listeners = ['rend' => 'render'];

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

    public function getRowsQueryProperty()
    {
        $query = MaterialHistory::query()->with('material.color', 'material.size', 'material.unit', 'audi')
            ->where(function ($query) {
                $query->whereHas('material', function($query) {
                    $query->whereRaw("name LIKE \"%$this->searchTerm%\"")
                        ->orWhereRaw("part_number LIKE \"%$this->searchTerm%\"");
                })->orWhere('stock', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('price', 'like', '%' . $this->searchTerm . '%');
            });
        
        if($this->dateInput){
            $query->when($this->dateInput, function ($query) {
                if($this->dateInput < Carbon::today()->subYear()){ 
                    $this->emit('swal:alert', [
                       'icon' => 'warning',
                        'title'   => 'Limitado a un año', 
                    ]);

                    $this->clearFilterDate();
                }
                elseif($this->dateInput >= Carbon::today()->tomorrow()->format('Y-m-d')){
                    $this->emit('swal:alert', [
                       'icon' => 'warning',
                        'title'   => 'No puedes consultar datos del futuro', 
                    ]);

                    $this->clearFilterDate();
                }
                else{
                    empty($this->dateOutput) ?
                        $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', now()]) :
                        $query->whereBetween('updated_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59']);
                }
            });
        }
        else{
            $query->whereMonth('created_at', now()->month);
        }

        return $query->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->created_at)->isoFormat('dddd D \d\e MMMM');
            });
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
        return view('backend.material.livewire.material-records-history-group', [
            'materials' => $this->rows,
        ]);
    }
}

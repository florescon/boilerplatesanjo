<?php

namespace App\Http\Livewire\Backend\Ticket;

use Livewire\Component;
use App\Domains\Auth\Models\User;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class AssignmentHistory extends Component
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

    public User $user;
    public int $user_id;
    public $name;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->user_id = $user->id;
        $this->name = $user->name ?? '';
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
        $attribute = User::findOrFail($this->user_id);

        $query = $attribute->assignments()->with('assignment.assignmentable.product.parent', 'assignment.assignmentable.product.color', 'assignment.assignmentable.product.size')->orderBy('created_at', 'desc');

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
            $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }

        return $query->get();
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery;
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

    public function render()
    {
        return view('backend.ticket.livewire.assignment-history', [
            'assignments' => $this->rows,
        ]);
    }
}

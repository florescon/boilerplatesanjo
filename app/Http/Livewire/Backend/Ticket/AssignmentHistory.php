<?php

namespace App\Http\Livewire\Backend\Ticket;

use Livewire\Component;
use App\Domains\Auth\Models\User;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;

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

        $query = $attribute->assignments()->with('assignment.assignmentable.product.parent', 'assignment.assignmentable.product.color', 'assignment.assignmentable.product.size')->orderBy('created_at', 'desc')->get();
        
        return $query;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery;
        });
    }

    public function render()
    {
        return view('backend.ticket.livewire.assignment-history', [
            'assignments' => $this->rows,
        ]);
    }
}

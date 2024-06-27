<?php

namespace App\Http\Livewire\Backend\Dashboard;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder;
use Livewire\Component;
use DB;
use Illuminate\Support\Arr;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;

class Quotation extends Component
{
    use WithBulkActions, WithCachedRows;

    public $limitPerPage = 50;

    public $searchTerm = '';

    public $perPage = '50';

    protected $listeners = [
        'load-more' => 'loadMore',
    ];
   
    public $pageQuotations;
    public $pageCaptured;
    public $pageProduction;

    public function mount()
    {
        $this->pageQuotations = array($this->limitPerPage, 'pageQuotations');
        $this->pageCaptured = array($this->limitPerPage, 'pageCaptured');
        $this->pageProduction = array($this->limitPerPage, 'pageProduction');
    }

    public function loadMore(?string $typeLoad)
    {
        $this->$typeLoad = Arr::set($this->$typeLoad, '0', $this->$typeLoad[0] + $this->limitPerPage);
    }

    private function productsOrder(): Builder
    {
        return $products_order = DB::table('product_order as b')
                ->select('order_id', DB::raw('sum(quantity) as sum, count(*) as total_products'))
                ->groupBy('order_id');
    }

    private function user(): Builder
    {
        return $user = DB::table('users as c')
                ->select('id as id_user', DB::raw('name as customer'));
    }

    public function getRowsQueryProperty()
    {
        return $quotations = DB::table('orders as a')
                ->joinSub($this->productsOrder(), 'products', function (JoinClause $join) {
                    $join->on('a.id', '=', 'products.order_id');
                })
                ->joinSub($this->user(), 'user', function (JoinClause $join) {
                    $join->on('a.user_id', '=', 'user.id_user');
                })
                ->select('*', DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y") as date'))
                ->where([
                    ['a.type', '=', '6'],
                    ['a.branch_id', '=', false],
                    ['a.deleted_at', '=', null],
                    ['a.flowchart', '=', true],
                    ['a.from_store', '=', null]
                ])
                ->where(function ($query) {
                    $query->where('a.comment', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('customer', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('a.info_customer', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('a.request', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('a.observation', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('a.folio', 'like', '%' . $this->searchTerm . '%');
                })
                ->orderByDesc('a.id');
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate(head($this->pageQuotations), ['*'], last($this->pageQuotations));
        });
    }


    public function render()
    {

        return view('backend.dashboard.livewire.quotation', [
          'quotations' => $this->rows,
        ]);
    }

}

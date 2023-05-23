<?php

namespace App\Http\Livewire\Backend\Store;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder;
use Livewire\Component;
use DB;
use Illuminate\Support\Arr;

class KanbanStore extends Component
{
    public $limitPerPage = 5;

    protected $listeners = [
        'load-more' => 'loadMore',
    ];

    public $pageQuotations;
    public $pageRequests;
    public $pageServices;

    public function mount()
    {
        $this->pageQuotations = array($this->limitPerPage, 'pageQuotations');
        $this->pageRequests = array($this->limitPerPage, 'pageRequests');
        $this->pageServices = array($this->limitPerPage, 'pageServices');
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

    private function productServiceOrders(): Builder
    {
        return $products_order = DB::table('product_service_orders as b')
                ->select('service_order_id', DB::raw('sum(quantity) as sum, count(*) as total_products'))
                ->groupBy('service_order_id');
    }

    private function user(): Builder
    {
        return $user = DB::table('users as c')
                ->select('id as id_user', DB::raw('name as customer'));
    }

    private function service(): Builder
    {
        return $service = DB::table('service_types as e')
                ->select('id as id_service', DB::raw('e.name as name_service'));
    }

    public function mainFunction()
    {
        return DB::table('orders as a')
                ->joinSub($this->productsOrder(), 'products', function (JoinClause $join) {
                    $join->on('a.id', '=', 'products.order_id');
                })
                ->joinSub($this->user(), 'user', function (JoinClause $join) {
                    $join->on('a.user_id', '=', 'user.id_user');
                })
                ;
    }

    private function quotations()
    {
        return $quotations = $this->mainFunction()
                ->select('*', DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y") as date'))
                ->where([
                    ['a.type', '=', '6'],
                    ['a.branch_id', '=', 1],
                    ['a.deleted_at', '=', null],
                    ['a.from_store', '=', true]
                ])
                ->orderByDesc('a.id');
    }

    private function requests_pendings()
    {
        return $requests_pendings = $this->mainFunction()
                ->where(function (Builder $query) {
                    $query->select('type')
                        ->from('orders_deliveries')
                        ->whereColumn('orders_deliveries.order_id', 'a.id')
                        ->orderByDesc('orders_deliveries.created_at')
                        ->limit(1);
                }, 'pending')
                ->select('*', DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y") as date'))
                ->where([
                    ['a.type', '=', '5'],
                    ['a.branch_id', '=', 1],
                    ['a.deleted_at', '=', null],
                    ['a.from_store', '=', true]
                ])
                ->orderByDesc('a.id');
    }

    private function services_pendings()
    {
        return $services_pendings = DB::table('service_orders as a')
                ->joinSub($this->productServiceOrders(), 'products', function (JoinClause $join) {
                    $join->on('a.id', '=', 'products.service_order_id');
                })
                ->leftJoinSub($this->user(), 'user', function (JoinClause $join) {
                    $join->on('a.user_id', '=', 'user.id_user');
                })
                ->leftJoinSub($this->service(), 'service', function (JoinClause $join) {
                    $join->on('a.service_type_id', '=', 'service.id_service');
                })
                ->select('*', DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y") as date'))
                ->where([
                    ['a.branch_id', '=', 1],
                    ['a.deleted_at', '=', null],
                    ['a.done', '=', false]
                ])
                ->orderByDesc('a.id');
    }

    public function render()
    {
        $quotations = $this->quotations()->paginate(head($this->pageQuotations), ['*'], last($this->pageQuotations));
        $requests_pendings = $this->requests_pendings()->paginate(head($this->pageRequests), ['*'], last($this->pageRequests));
        $services_pendings = $this->services_pendings()->paginate(head($this->pageServices), ['*'], last($this->pageServices));

        return view('backend.store.dashboard.kanban-store', [
            'quotations' => $quotations,
            'requests_pendings' => $requests_pendings,
            'services_pendings' => $services_pendings,
        ]);
    }
}

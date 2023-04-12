<?php

namespace App\Http\Livewire\Backend\Dashboard;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder;
use Livewire\Component;
use DB;

class Kanban extends Component
{
    public $limitPerPage = 10;

    protected $listeners = [
        'load-more' => 'loadMore',
    ];
   
    public function loadMore()
    {
        $this->limitPerPage = $this->limitPerPage + 10;
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

    private function status(): Builder
    {
        return $status = DB::table('statuses as e')
                ->select('id as id_status', DB::raw('e.name as name_status, e.percentage as percentage_status'));
    }

    private function lastStatusOrder(): Builder
    {
        return $status_orders = DB::table('status_orders as d')
                            ->select('order_id', DB::raw('MAX(id) as last_status_order_id'))
                            ->groupBy('order_id');
    }

    private function statusOrder(): Builder
    {
        return $status_ordersB = DB::table('status_orders as f')
                ->joinSub($this->status(), 'status', function (JoinClause $join) {
                    $join->on('f.status_id', '=', 'status.id_status');
                })
                ->select('id', 'status_id', 'name_status', 'percentage_status');
    }

    private function orderProcess(?int $type = 1): Builder
    {
        return $orders_captured = DB::table('orders as a')
                ->joinSub($this->productsOrder(), 'products', function (JoinClause $join) {
                    $join->on('a.id', '=', 'products.order_id');
                })
                ->leftJoinSub($this->user(), 'user', function (JoinClause $join) {
                    $join->on('a.user_id', '=', 'user.id_user');
                })
                ->where(function (Builder $query) use($type) {
                    $query->select('status_id')
                        ->from('status_orders')
                        ->whereColumn('status_orders.order_id', 'a.id')
                        ->orderByDesc('status_orders.created_at')
                        ->limit(1);
                }, $type)
                ->joinsub($this->lastStatusOrder(), 'status_order', function (JoinClause $join) {
                    $join->on('a.id', '=', 'status_order.order_id');
                })
                ->leftJoinSub($this->statusOrder(), 'status_orderB', function (JoinClause $join) {
                    $join->on('last_status_order_id', '=', 'status_orderB.id');
                })
                ->select('*', DB::raw('a.id as id, DATE_FORMAT(a.created_at, "%d-%m-%Y") as date'))
                ->where([
                    ['a.type', '=', '1'],
                    ['a.branch_id', '=', false],
                    ['a.deleted_at', '=', null],
                    ['a.from_store', '=', null]
                ])
                ->orderBy('a.id', 'desc')
                 // ->union($orders_captured2)
                ->orderByDesc('a.id')
                ->limit(10);
    }

    private function countBeforeGet($getData)
    {
        return $getData->count();
    }

    private function quotations()
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
                    ['a.from_store', '=', null]
                ])
                ->orderByDesc('a.id');
    }  

    private function ordersToBeDefined()
    {
        return  $orders_to_be_defined = DB::table('orders as a')
                 ->joinSub($this->productsOrder(), 'products', function (JoinClause $join) {
                    $join->on('a.id', '=', 'products.order_id');
                 })
                 ->leftJoinSub($this->user(), 'user', function (JoinClause $join) {
                    $join->on('a.user_id', '=', 'user.id_user');
                 })
                ->where(function ($query) {
                    $query->select('status_id')
                        ->from('status_orders')
                        ->whereColumn('status_orders.order_id', 'a.id')
                        ->orderByDesc('status_orders.created_at')
                        ->limit(1);
                }, null)
                ->select('*', DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y") as date'))
                ->where([
                    ['a.type', '=', '1'],
                    ['a.branch_id', '=', false],
                    ['a.deleted_at', '=', null],
                    ['a.from_store', '=', null]
                ])
                ->orderBy('a.id', 'desc')->limit(10);
    }

    public function render()
    {
        $quotations = $this->quotations()->paginate($this->limitPerPage);

        $orders_to_be_defined = $this->ordersToBeDefined()->get();

        $orders_captured = $this->orderProcess(1)->paginate($this->limitPerPage);

        $orders_court = $this->orderProcess(4)->get();

        $orders_making = $this->orderProcess(6)->get();

        $orders_revision = $this->orderProcess(7)->get();

        $orders_personalization = $this->orderProcess(8)->get();

        $orders_revision_final = $this->orderProcess(9)->get();

        $orders_finalized = $this->orderProcess(2)->paginate($this->limitPerPage);

        // echo "<pre>";
        // print_r($orders_captured);
        // echo "</pre>";

        return view('backend.dashboard.livewire.kanban', [
          'quotations' => $quotations,
          'orders_to_be_defined' => $orders_to_be_defined,
          'orders_captured' => $orders_captured,
          'orders_court' => $orders_court,
          'orders_making' => $orders_making,
          'orders_revision' => $orders_revision,
          'orders_personalization' => $orders_personalization,
          'orders_revision_final' => $orders_revision_final,
          'orders_finalized' => $orders_finalized,
        ]);
    }
}

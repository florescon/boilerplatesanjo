<?php

namespace App\Http\Livewire\Backend\Bom;

use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder;
use App\Models\Order;
use Illuminate\Support\Arr;
use DB;

class BomTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    public $perPage = 10;

    public array $types = [1, 6];

    public $selectedtypes = [];

    public $materials;

    protected $listeners = [
        'load-more' => 'loadMore',
    ];
   
    public function loadMore()
    {
        $this->perPage = $this->perPage + 10;
    }

    private function user(): Builder
    {
        return $user = DB::table('users as c')
                ->select('id as id_user', DB::raw('name as customer'));
    }

    public function getRowsQueryProperty()
    {
        $orders = DB::table('orders as a')->leftJoinSub($this->user(), 'user', function (JoinClause $join) {
                    $join->on('a.user_id', '=', 'user.id_user');
                })
                ->whereIn('a.type', $this->types)
                ->where([
                    ['a.branch_id', '=', false],
                    ['a.deleted_at', '=', null],
                    ['a.from_store', '=', null]
                ])
                ->orderByDesc('a.id');

        return $orders;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    private function getSelectedProducts()
    {
        return $this->selectedtypes;
    }

    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);

        $collect = collect();

        foreach($this->getSelectedProducts() as $orderID){
            $order = Order::find($orderID);
            foreach($order->product_order as $product_order){
                if($product_order->gettAllConsumption() != 'empty'){
                    foreach($product_order->gettAllConsumption() as $key => $consumption){
                        $collect->push([
                            'order' => $orderID,
                            'product_order_id' => $product_order->id, 
                            'material_name' => $consumption['material'],
                            'material_id' => $key,
                            'unit' => $consumption['unit'],
                            'quantity' => $consumption['quantity'],
                        ]);
                    }
                }
            }            
        }

        $collection = $collect->groupBy('material_id')->map(function ($row) {
                    return [
                        'order' => $row[0]['order'],
                        'product_order_id' => $row[0]['product_order_id'], 
                        'material_name' => $row[0]['material_name'],
                        'material_id' => $row[0]['material_id'],
                        'unit' => $row[0]['unit'],
                        'quantity' => $row->sum('quantity')
                    ];
                })->toArray();

        $this->materials = $collection;
    }


    public function render()
    {
        $orders = DB::table('orders as a')->leftJoinSub($this->user(), 'user', function (JoinClause $join) {
                    $join->on('a.user_id', '=', 'user.id_user');
                })
                ->whereIn('a.type', $this->types)
                ->where([
                    ['a.branch_id', '=', false],
                    ['a.deleted_at', '=', null],
                    ['a.from_store', '=', null]
                ])
                ->orderByDesc('a.id');

        return view('backend.bom.livewire.bom-table', [
          'orders' => $this->rows,
          'materials' => $this->materials,
        ]);
    }
}

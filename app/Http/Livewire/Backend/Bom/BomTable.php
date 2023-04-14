<?php

namespace App\Http\Livewire\Backend\Bom;

use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder;
use Excel;
use App\Exports\BillOfMaterialsExport;
use App\Models\Order;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use DB;

class BomTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows;

    public $perPage = 10;

    public array $types = [1, 6];

    public $selectedtypes = [];

    public $searchTerm = '';

    public $searchFeedstock = '';

    public $searchProduct = '';

    public $materials;

    public $products;

    public $selectedOrders;

    public $orderCollection;

    public $tab = 'members';

    protected $listeners = [
        'load-more' => 'loadMore',
    ];

    protected $messages = [
        'selectedtypes.max' => 'Máximo 10 registros.',
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

    private function status(): Builder
    {
        return $status = DB::table('statuses as e')
                ->select('id as id_status', DB::raw('e.name as name_status, e.percentage as percentage_status'));
    }

    private function lastStatusOrder(): Builder
    {
        return $status_orders = DB::table('status_orders as d')
                            ->select('order_id', DB::raw('MAX(id) as last_status_order_id, count(*) total_status'))
                            ->groupBy('order_id');
    }

    private function statusOrder(): Builder
    {
        return $status_ordersB = DB::table('status_orders as f')
                ->joinSub($this->status(), 'status', function (JoinClause $join) {
                    $join->on('f.status_id', '=', 'status.id_status');
                })
                ->select('f.id', 'f.status_id', 'name_status', 'percentage_status');
    }

    public function getRowsQueryProperty()
    {
        $orders = DB::table('orders as a')
                ->leftJoinSub($this->user(), 'user', function (JoinClause $join) {
                    $join->on('a.user_id', '=', 'user.id_user');
                })
                ->leftJoinSub($this->lastStatusOrder(), 'status', function (JoinClause $join) {
                    $join->on('a.id', '=', 'status.order_id');
                })
                ->leftJoinSub($this->statusOrder(), 'status_orderB', function (JoinClause $join) {
                    $join->on('last_status_order_id', '=', 'status_orderB.id');
                })
                ->whereIn('a.type', $this->types)
                ->select('*', DB::raw('a.id as id, DATE_FORMAT(a.created_at, "%d-%m-%Y") as date'))
                ->where([
                    ['a.deleted_at', '=', null],
                ]);

        $this->applySearchFilter($orders);

        return $orders->orderByDesc('a.id');
    }

    private function applySearchFilter($order)
    {
        if ($this->searchTerm) {
            return $order->where(function(Builder $query) {
                $query->whereRaw("a.id LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("a.comment LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("info_customer LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("request LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("purchase LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("name_status LIKE \"%$this->searchTerm%\"");
            });
        }

        return null;
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

    public function sendMaterials()
    {   
        $this->validate([
            'selectedtypes' => 'max:10',
        ]);

        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();

        foreach($this->getSelectedProducts() as $orderID){
            $order = Order::with('products.consumption_filter.material', 'products.parent')->find($orderID);

            $ordercollection->push([
                'id' => $order->id,
                'user' => optional($order->user)->name,
                'comment' => $order->comment,
            ]);

            foreach($order->products as $product_order){

                $productsCollection->push([
                    'productId' => $product_order->id,
                    'productParentId' => $product_order->product->parent_id ?? null,
                    'productParentName' => $product_order->product->only_name ?? null,
                    'productParentCode' => $product_order->product->parent_code ?? null,
                    'productOrder' => $product_order->order_id,
                    'productName' => $product_order->product->full_name_clear ?? null,
                    'productColor' => $product_order->product->color_id,
                    'productColorName' => $product_order->product->color->name ?? '',
                    'productQuantity' => $product_order->quantity,
                ]);

                if($product_order->gettAllConsumption() != 'empty'){
                    foreach($product_order->gettAllConsumption() as $key => $consumption){
                        $consumptionCollect->push([
                            'order' => $orderID,
                            'product_order_id' => $product_order->id, 
                            'material_name' => $consumption['material'],
                            'part_number' => $consumption['part_number'],
                            'material_id' => $key,
                            'unit' => $consumption['unit'],
                            'unit_measurement' => $consumption['unit_measurement'],
                            'vendor' => $consumption['vendor'],
                            'family' => $consumption['family'],
                            'quantity' => $consumption['quantity'],
                            'stock' => $consumption['stock'],
                        ]);
                    }
                }
            }
        }

        $this->products = $productsCollection->groupBy(['productParentId', function ($item) {
            return $item['productColor'];
        }], $preserveKeys = false);

        $materials = $consumptionCollect->groupBy('material_id')->map(function ($row) {
                    return [
                        'order' => $row[0]['order'],
                        'product_order_id' => $row[0]['product_order_id'], 
                        'material_name' => $row[0]['material_name'],
                        'part_number' => $row[0]['part_number'],
                        'material_id' => $row[0]['material_id'],
                        'unit' => $row[0]['unit'],
                        'unit_measurement' => $row[0]['unit_measurement'],
                        'vendor' => $row[0]['vendor'],
                        'family' => $row[0]['family'],
                        'quantity' => $row->sum('quantity'),
                        'stock' => $row[0]['stock'],
                    ];
                });

        $this->materials = $materials;

        $this->orderCollection = $ordercollection->toArray();

        // dd($this->products->toArray());

        // $this->selectedOrders = $this->rows->whereIn('id', $this->getSelectedProducts())->toArray();
    }

    private function products()
    {
        return $this->products = $this->products ? $this->products->sortBy(['productName', 'asc']) : null;
    }

    private function materials()
    {
        return $this->materials = $this->materials ? $this->materials->sortBy(['unit_measurement', 'asc'],['material_name', 'asc']) : null;
    }

    private function applySearchFeedstock($collectionFeedstock)
    {
        if ($this->searchFeedstock) {
            $searchFeedstock = strtolower($this->searchFeedstock);

            return $collectionFeedstock->filter(function ($item) use($searchFeedstock){
                return preg_match("/$searchFeedstock/",strtolower($item['material_name'].' '.$item['part_number'].' '.$item['vendor'].' '.$item['unit_measurement'].' '.$item['family']));
            });

        }

        return $this->materials();
    }

    public function exportMaatwebsiteCustom($extension, ?string $sort = '')
    {   
        if($sort == 'vendor'){
            $this->materials = $this->materials->sortBy(['vendor', 'asc'],['material_name', 'asc']);
        }
        else{
            $this->materials = $this->materials->sortBy(['unit_measurement', 'asc'],['material_name', 'asc']);
        }

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new BillOfMaterialsExport($this->materials->toArray()), 'bill-of-materials-'.Carbon::now().'.'.$extension);
    }

    public function render()
    {
        // echo "<pre>";
        // print_r($this->rows);
        // echo "</pre>";

        return view('backend.bom.livewire.bom-table', [
          'orders' => $this->rows,
          'materialsCollection' => $this->materials ? $this->applySearchFeedstock($this->materials()) : null,
          'productsCollection' => $this->products ? $this->products() : null,
          'orderCollection' => $this->orderCollection,
        ]);
    }
}

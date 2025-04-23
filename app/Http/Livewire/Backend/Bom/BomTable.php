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
use App\Exports\ProductsBomExport;
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

    public $productsCollection;

    public $tab = 'members';

    public bool $history = false;

    public $lastProcessId;

    protected $listeners = [
        'load-more' => 'loadMore',
    ];

    protected $messages = [
        'selectedtypes.max' => 'Máximo 25 registros.',
    ];


    // Método de ciclo de vida de Livewire, se ejecuta cuando el componente se monta
    public function mount()
    {
        // Asignar el id del último proceso a la propiedad pública
        $this->lastProcessId = Order::getLastProcess()->id;
    }

    public function loadMore()
    {
        $this->perPage = $this->perPage + 10;
    }

    public function isHistory()
    {
        $this->perPage = 10;

        if($this->history){
            $this->history = false;
        }
        else{
            $this->history = TRUE;
        }
    }

    private function user(): Builder
    {
        return $user = DB::table('users as c')
                ->select('id as id_user', DB::raw('name as customer'));
    }

    private function productOrderSumQuery(): Builder
    {
        return DB::table('product_order as po')
            ->select('po.order_id', DB::raw('SUM(po.quantity) as total_quantity'))
            ->groupBy('po.order_id');
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
        $lastProcessId = $this->lastProcessId;

        $orders = DB::table('orders as a')
                ->where('from_store', null)
                ->where('from_store', null)
                ->where('flowchart', true)
                ->leftJoinSub($this->user(), 'user', function (JoinClause $join) {
                    $join->on('a.user_id', '=', 'user.id_user');
                })
                ->leftJoinSub($this->productOrderSumQuery(), 'product_order_sum', function (JoinClause $join) {
                    $join->on('a.id', '=', 'product_order_sum.order_id');
                })
                ->whereIn('a.type', $this->types)
                ->select('*', DB::raw('a.id as id, DATE_FORMAT(a.created_at, "%d-%m-%Y") as date'))
                ->where([
                    ['a.branch_id', '=', 0],
                    ['a.deleted_at', '=', null],
                ])
                ->when(!$this->history, function ($query) use ($lastProcessId) {
                    $query->where(function ($query2) use ($lastProcessId) {
                        $query2->whereRaw("
                            EXISTS (
                                SELECT 1
                                FROM product_order AS po_sub
                                LEFT JOIN (
                                    SELECT product_order_id, SUM(quantity) as total_received
                                    FROM product_station_receiveds
                                    WHERE status_id = ?
                                    GROUP BY product_order_id
                                ) AS psr ON po_sub.id = psr.product_order_id
                                LEFT JOIN (
                                    SELECT product_order_id, SUM(out_quantity) as total_out
                                    FROM product_station_outs
                                    GROUP BY product_order_id
                                ) AS pso ON po_sub.id = pso.product_order_id
                                WHERE po_sub.order_id = a.id
                                AND (psr.total_received IS NULL OR psr.total_received < po_sub.quantity)
                                AND (pso.total_out IS NULL OR pso.total_out < po_sub.quantity)
                            )
                        ", [$lastProcessId]);
                    });
                });

        $this->applySearchFilter($orders);

        return $orders->orderByDesc('a.created_at');
    }

    private function applySearchFilter($order)
    {
        if ($this->searchTerm) {
            return $order->where(function(Builder $query) {
                $query->whereRaw("a.folio LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("a.comment LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("a.complementary LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("info_customer LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("customer LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("request LIKE \"%$this->searchTerm%\"")
                ->orWhereRaw("purchase LIKE \"%$this->searchTerm%\"");
                // ->orWhereRaw("name_status LIKE \"%$this->searchTerm%\"");
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

    public function removeSelected()
    {
        return $this->selectedtypes = [];
    }

    public function sendMaterials()
    {   
        $this->validate([
            'selectedtypes' => 'max:25',
        ]);

        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();

        $orders = Order::whereIn('id', $this->getSelectedProducts())->with('products.consumption_filter.material', 'products.parent', 'products.order.user.customer')->get();

        foreach($orders as $order){

            $ordercollection->push([
                'id' => $order->id,
                'folio' => $order->folio,
                'user' => optional($order->user)->name,
                'type' => $order->characters_type_order,
                'comment' => $order->comment,
                'complementary' => $order->complementary,
            ]);

            foreach($order->products as $product_order){

                $productsCollection->push([
                    'productId' => $product_order->id,
                    'productParentId' => $product_order->product->parent_id ?? $product_order->product_id,
                    'productParentName' => $product_order->product->only_name ?? null,
                    'productParentCode' => $product_order->product->parent_code ?? null,
                    'productOrder' => $product_order->order->folio_or_id_clear,
                    'productName' => $product_order->product->full_name_clear ?? null,
                    'productColor' => $product_order->product->color_id,
                    'productColorName' => $product_order->product->color->name ?? '',
                    'productQuantity' => $product_order->quantity,
                    'isService' => !$product_order->product->parent_id ? true : false,
                    'customer' => $product_order->order->user_name ?? null,
                ]);

                if($product_order->gettAllConsumption() != 'empty'){
                    foreach($product_order->gettAllConsumption() as $key => $consumption){
                        $consumptionCollect->push([
                            'order' => $order->id,
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

        $this->productsCollection = $productsCollection;

        // dd($this->productsCollection);

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
        elseif($sort == 'family'){
            $this->materials = $this->materials->sortBy(['family', 'asc'],['material_name', 'asc']);
        }
        else{
            $this->materials = $this->materials->sortBy(['unit_measurement', 'asc'],['material_name', 'asc']);
        }

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new BillOfMaterialsExport($this->materials->toArray()), 'bill-of-materials-'.Carbon::now().'.'.$extension);
    }

    public function printTicket()
    {
        return redirect()->route('admin.bom.ticket_bom', urlencode(json_encode($this->getSelectedProducts())));
    }

    public function exportProductsCustom($extension, ?string $sort = '')
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new ProductsBomExport($this->productsCollection), 'bom-products-'.Carbon::now().'.'.$extension);
    }

    public function render()
    {
        // echo "<pre>";
        // print_r($this->rows);
        // echo "</pre>";

        return view('backend.bom.livewire.bom-table', [
          'orders' => $this->rows,
          'materialsCollection' => $this->materials ? $this->applySearchFeedstock($this->materials()) : null,
          'productsCollectionGrouped' => $this->products ? $this->products() : null,
          'orderCollection' => $this->orderCollection,
        ]);
    }
}

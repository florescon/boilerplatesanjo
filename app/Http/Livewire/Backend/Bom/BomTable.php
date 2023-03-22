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

    public $materials;

    public $selectedOrders;

    public $orderCollection;

    public $tab = 'members';

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
                ]);

        $this->applySearchFilter($orders);

        return $orders->orderByDesc('a.id');
    }

    private function applySearchFilter($order)
    {
        if ($this->searchTerm) {
            return $order->whereRaw("id LIKE \"%$this->searchTerm%\"");
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

        $collect = collect();
        $ordercollection = collect();

        foreach($this->getSelectedProducts() as $orderID){
            $order = Order::with('products.consumption_filter.material', 'products.parent')->find($orderID);

            $ordercollection->push([
                'id' => $order->id,
                'user' => optional($order->user)->name,
                'comment' => $order->comment,
            ]);

            foreach($order->products as $product_order){
                if($product_order->gettAllConsumption() != 'empty'){
                    foreach($product_order->gettAllConsumption() as $key => $consumption){
                        $collect->push([
                            'order' => $orderID,
                            'product_order_id' => $product_order->id, 
                            'material_name' => $consumption['material'],
                            'part_number' => $consumption['part_number'],
                            'material_id' => $key,
                            'unit' => $consumption['unit'],
                            'unit_measurement' => $consumption['unit_measurement'],
                            'vendor' => $consumption['vendor'],
                            'quantity' => $consumption['quantity'],
                            'stock' => $consumption['stock'],
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
                        'part_number' => $row[0]['part_number'],
                        'material_id' => $row[0]['material_id'],
                        'unit' => $row[0]['unit'],
                        'unit_measurement' => $row[0]['unit_measurement'],
                        'vendor' => $row[0]['vendor'],
                        'quantity' => $row->sum('quantity'),
                        'stock' => $row[0]['stock'],
                    ];
                });

        $this->materials = $collection;

        $ss = $this->rows
            ->map(fn ($name) => $name->id);

        $this->orderCollection = $ordercollection->toArray();

        // $this->selectedOrders = $this->rows->whereIn('id', $this->getSelectedProducts())->toArray();
    }

    public function exportMaatwebsiteCustom($extension, ?string $sort = '')
    {   
        if($sort == 'vendor'){
            $this->materials = $this->materials->sortBy(['vendor', 'asc'],['material_name', 'asc']);
        }
        else{
            $this->materials = $this->materials->sortBy(['part_number', 'asc'],['material_name', 'asc']);
        }

        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new BillOfMaterialsExport($this->materials->toArray()), 'bill-of-materials-'.Carbon::now().'.'.$extension);
    }

    public function render()
    {
        $this->materials = $this->materials->sortBy(['part_number', 'asc'],['material_name', 'asc']);

        return view('backend.bom.livewire.bom-table', [
          'orders' => $this->rows,
          'materials' => $this->materials ? $this->materials->toArray() : null,
          'orderCollection' => $this->orderCollection,
        ]);
    }
}

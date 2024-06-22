<?php

namespace App\Http\Livewire\Backend\Information;

use Livewire\Component;
use App\Models\Status;
use App\Models\Additional;
use App\Models\ProductStation;
use Illuminate\Support\Facades\Auth;
use DB;

class AddToMateria extends Component
{
    public $status;
    public $queryModal;

    public $type = 'feedstock';
    public $branchId = 0;

    protected $listeners = ['clearAllFeedstocks', 'cartUpdated' => '$refresh'];

    public function mount(Status $status)
    {
        $this->status = $status;
    }

    public function show($id)
    {
        $this->queryModal = ProductStation::query()->with('product', 'status')
            ->where('status_id', $id)
            ->where('active', true)
            ->where('not_consider', false)
            ->get();
    }

    private function getFeedstocks()
    {
        return $productsAdditionals = Additional::with('material.color', 'material.vendor', 'product.size')->where('type', $this->type)->where('branch_id', $this->branchId)->where('date_entered', null)->where('user_id', Auth::id())->get();
    }    

    public function removeFeedstock($feedstockId): void
    {
        $delete = DB::table('additionals')->where('id', $feedstockId)->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function clearAllFeedstocks()
    {
        DB::table('additionals')->where('type', $this->type)->where('branch_id', $this->branchId)->where('date_entered', null)->where('user_id', Auth::id())->delete();
    }

    public function render()
    {
        if(($this->status->active != true) || !$this->status->initial_lot){
            abort(401);
        }

        $query = ProductStation::query()->with('product', 'status')
            ->where('status_id', $this->status->id)
            ->where('active', true)
            ->where('not_consider', false)
            ->get();

        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();

        $parentQuantities = [];

        foreach ($query as $product_statione) {
            $quantity = $product_statione->quantity;

            $ordercollection->push([
                'order_id' => $product_statione->order_id,
                'station_id' => $product_statione->station_id,
            ]);

            $productParentId = $product_statione->product->parent_id ?? $product_statione->product_id;

            $productsCollection->push([
                'productId' => $product_statione->id,
                'productParentId' => $productParentId,
                'productParentName' => $product_statione->product->only_name ?? null,
                'productParentCode' => $product_statione->product->parent_code ?? null,
                'productOrder' => $product_statione->order_id,
                'productName' => $product_statione->product->parent->name ?? null,
                'productColor' => $product_statione->product->color_id,
                'productColorName' => $product_statione->product->color->name ?? '',
                'productSizeName' => $product_statione->product->size->name ?? '',
                'productSizeSort' => $product_statione->product->size->sort ?? '',
                'productQuantity' => $quantity,
                'isService' => !$product_statione->product->parent_id ? true : false,
                'customer' => $product_statione->order->user_name ?? null,
            ]);

            if (!isset($parentQuantities[$productParentId])) {
                $parentQuantities[$productParentId] = 0;
            }
            $parentQuantities[$productParentId] += $quantity;

            if ($product_statione->product_order->gettAllConsumptionSecond($quantity) != 'empty') {
                foreach ($product_statione->product_order->gettAllConsumptionSecond($quantity) as $key => $consumption) {
                    $consumptionCollect->push([
                        'order' => $product_statione->order_id,
                        'product_order_id' => $product_statione->product_order->id,
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

        $materials = $consumptionCollect->groupBy('vendor')->map(function ($rowsByMaterial) {
            return $rowsByMaterial->groupBy('material_id')->map(function ($rowsByVendor) {
                return [
                    'order' => $rowsByVendor[0]['order'],
                    'product_order_id' => $rowsByVendor[0]['product_order_id'],
                    'material_name' => $rowsByVendor[0]['material_name'],
                    'part_number' => $rowsByVendor[0]['part_number'],
                    'material_id' => $rowsByVendor[0]['material_id'],
                    'unit' => $rowsByVendor[0]['unit'],
                    'unit_measurement' => $rowsByVendor[0]['unit_measurement'],
                    'vendor' => $rowsByVendor[0]['vendor'],
                    'family' => $rowsByVendor[0]['family'],
                    'quantity' => $rowsByVendor->sum('quantity'),
                    'stock' => $rowsByVendor[0]['stock'],
                ];
            });
        });

        // dd($materials);

        $ordercollectionn = $ordercollection->groupBy(['order_id', 'station_id'])->toArray();

        $productsCollection = $productsCollection->sortBy('productParentId');
        $groupedProducts = $productsCollection->groupBy('productParentId');

        // dd($parentQuantities);

        return view('backend.information.livewire.add-to-materia',[
            'getFeedstocks' => $this->getFeedstocks(),
            'productsCollection' => $productsCollection,
            'materials' => $materials,
            'ordercollectionn' => $ordercollectionn,
            'parentQuantities' => $parentQuantities,
            'groupedProducts' => $groupedProducts,

        ]);
    }
}

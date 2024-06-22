<?php

namespace App\Http\Livewire\Backend\Information;

use Livewire\Component;
use App\Models\Status;
use App\Models\Additional;
use App\Models\ProductStation;
use Illuminate\Support\Facades\Auth;
use DB;

class AddToVendor extends Component
{
    public $status;

    public $type = 'vendor';
    public $branchId = 0;

    protected $listeners = ['clearAllProducts', 'cartUpdated' => '$refresh'];

    public function mount(Status $status)
    {
        $this->status = $status;
    }

    private function getProducts()
    {
        return $productsAdditionals = Additional::with('product.color', 'product.parent', 'product.size')->where('type', $this->type)->where('branch_id', $this->branchId)->where('date_entered', null)->where('user_id', Auth::id())->get();
    }    

    public function removeProduct($productId): void
    {
        $delete = DB::table('additionals')->where('id', $productId)->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function clearAllProducts()
    {
        DB::table('additionals')->where('type', $this->type)->where('branch_id', $this->branchId)->where('date_entered', null)->where('user_id', Auth::id())->delete();
    }

    public function render()
    {
        // dd($this->getProducts());

        $query = ProductStation::query()->with('product', 'status')
            ->where('status_id', $this->status->id)
            ->where('active', true)
            ->where('not_consider', false)
            ->get();

        $query2 = $this->getProducts();

        $ordercollection = collect();
        $productsCollection = collect();

        $parentQuantities = [];


        $productsCollectionSecond = collect();

        foreach($query2 as $product_st){
            $quantity = $product_st->quantity;

            $productParentIdSecond = $product_st->product->parent_id ?? $product_st->product_id;

            $productsCollectionSecond->push([
                'productId' => $product_st->id,
                'productParentId' => $productParentIdSecond,
                'productParentName' => $product_st->product->only_name ?? null,
                'productParentCode' => $product_st->product->parent_code ?? null,
                'productOrder' => $product_st->order_id,
                'productName' => $product_st->product->full_name_clear ?? null,
                'productColor' => $product_st->product->color_id,
                'productColorName' => $product_st->product->color->name ?? '',
                'productSizeName' => $product_st->product->size->name ?? '',
                'productSizeSort' => $product_st->product->size->sort ?? '',
                'productQuantity' => $quantity,
                'isService' => !$product_st->product->parent_id ? true : false,
                'customer' => $product_st->order->user_name ?? null,
                'stationId' => $product_st->station_id,
                'orderId' => $product_st->order_id,
                'vendorId' => $product_st->product->vendor_id,
                'vendorName' => $product_st->product->parent->vendor->name ?? null,
                'vendorAddress' => $product_st->product->parent->vendor->address ?? null,
                'vendorCity' => $product_st->product->parent->vendor->city->city ?? null,
                'vendorPhone' => $product_st->product->parent->vendor->phone ?? null,
                'vendorEmail' => $product_st->product->parent->vendor->email ?? null,
                'vendorRfc' => $product_st->product->parent->vendor->rfc ?? null,
            ]);
        }

        // dd($productsCollectionSecond);

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
                'stationId' => $product_statione->station_id,
                'orderId' => $product_statione->order_id,
                'vendorId' => $product_statione->product->vendor_id,
                'vendorName' => $product_statione->product->parent->vendor->name ?? null,
                'vendorAddress' => $product_statione->product->parent->vendor->address ?? null,
                'vendorCity' => $product_statione->product->parent->vendor->city->city ?? null,
                'vendorPhone' => $product_statione->product->parent->vendor->phone ?? null,
                'vendorEmail' => $product_statione->product->parent->vendor->email ?? null,
                'vendorRfc' => $product_statione->product->parent->vendor->rfc ?? null,
            ]);

            if (!isset($parentQuantities[$productParentId])) {
                $parentQuantities[$productParentId] = 0;
            }
            $parentQuantities[$productParentId] += $quantity;
        }

        $ordercollectionn = $ordercollection->groupBy(['order_id', 'station_id'])->toArray();

        // dd($productsCollection->merge($productsCollectionSecond));

        $productsCollection = $productsCollection->sortBy('productParentId');
        $groupedProducts = $productsCollection->groupBy(['productParentId']);

        $groupedProductsSecond = $productsCollection->groupBy(['vendorName', 'productParentId']);

        // Ordenar cada grupo por productColorName y productSizeSort
        $groupedProductsSecond = $groupedProductsSecond->map(function ($vendorGroup) {
            return $vendorGroup->map(function ($parentGroup) {
                return $parentGroup->sortBy(function ($product) {
                    return $product['productColorName'] . ' ' . $product['productSizeSort'];
                });
            });
        });


        return view('backend.information.livewire.add-to-vendor', [
            'getProducts' => $this->getProducts(),
            'productsCollection' => $productsCollection,
            'ordercollectionn' => $ordercollectionn,
            'parentQuantities' => $parentQuantities,
            'groupedProducts' => $groupedProducts,
            'groupedProductsSecond' => $groupedProductsSecond,
        ]);
    }
}

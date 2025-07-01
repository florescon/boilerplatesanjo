<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Status;

class Report extends Component
{
    public $order_id;

    public bool $width = false;

    public $orderCollection;

    public $productsCollection;

    public function mount(Order $order)
    {
        $this->order_id = $order->id;
    }

    public function render()
    {
        $order = Order::with('products.product', 'user', 'stations.product_station.product', 'stations.product_station.product_station_receiveds')->find($this->order_id);

        $ordercollection = collect();

        $ordercollection->push([
            'id' => $order->id,
            'folio' => $order->folio,
            'user' => $order->user_name_clear ?? null,
            'userId' => $order->user_id ?? null,
            'comment' => $order->comment,
            'request' => $order->request,
        ]);

        $productsCollection = $order->products->map(function ($productOrder) use ($order) {
            return [
                'productId' => $productOrder->id,
                'productParentId' => $productOrder->product->parent_id ?? $productOrder->product_id,
                'productParentName' => $productOrder->product->only_name ?? null,
                'productParentCode' => $productOrder->product->parent_code ?? null,
                'productCode' => $productOrder->product->code ?? null,
                'productOrder' => $order->folio,
                'productName' => $productOrder->product->full_name_clear ?? null,
                'productColor' => $productOrder->product->color_id,
                'productColorName' => $productOrder->product->color->name ?? '',
                'productSize' => $productOrder->product->size_id,
                'productSizeName' => $productOrder->product->size->name ?? '',
                'productSizeSort' => $productOrder->product->size->sort ?? 0,
                'productQuantity' => $productOrder->quantity,
                'productPriceWithoutTax' => $productOrder->price_without_tax,
                'isService' => !$productOrder->product->parent_id ? true : false,
                'customerId' => $order->user_id ?? null,
                'customer' => $order->user_name_clear ?? null,
            ];
        });

        $this->orderCollection = $ordercollection->toArray();

        $this->products = $productsCollection->groupBy(['productParentId', function (array $item) {
            return $item['productParentName'].' - '.$item['productColorName'].' => '.$item['productParentCode'];
        }], $preserveKeys = true);



        $productsGrouped = $productsCollection->groupBy([
            'productParentId',
            function ($item) {
                return $item['productParentName'].' - '.$item['productColorName'].' => '.$item['productParentCode'];
            }
        ])->map(function ($group) {
            return $group->map(function ($items) {
                $totalQuantity = $items->sum('productQuantity');
                return [
                    'items' => $items,
                    'totalQuantity' => $totalQuantity
                ];
            });
        });

        $status = Status::orderBy('level')->whereActive(true)->get();

        return view('backend.order.print-report', [
            'status' => $status,
            'order' => $order,
            'ordercollection' => $this->orderCollection,
            'productsGrouped' => $productsGrouped,
        ]);
    }
}

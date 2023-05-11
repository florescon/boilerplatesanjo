<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;

class PrintExportOrders extends Component
{
    public $orders;

    public $orderCollection;

    public $productsCollection;

    public $products;

    private function products()
    {
        return $this->products = $this->products ? $this->products->sortBy(['productName', 'asc']) : null;
    }
    public function render()
    {
        $ordercollection = collect();
        $productsCollection = collect();

        $oss = collect();

        if($this->orders){

            // $omg = Order::where('from_store', null)->whereIn('type', [1, 6])->get();

            // foreach($omg as $om){
            //     $oss->push([
            //         $om->id, 
            //     ]);
            // }

            // dd($oss->values()->all());
            
            $ordersJson = Order::whereIn('id', json_decode($this->orders))->with('products', 'user.customer')->get();

            foreach($ordersJson as $order){

                $ordercollection->push([
                    'id' => $order->id,
                    'user' => $order->user_name_clear ?? null,
                    'userId' => $order->user_id ?? null,
                    'comment' => $order->comment,
                    'request' => $order->request,
                ]);

                foreach($order->products as $product_order){

                    $productsCollection->push([
                        'productId' => $product_order->id,
                        'productParentId' => $product_order->product->parent_id ?? $product_order->product_id,
                        'productParentName' => $product_order->product->only_name ?? null,
                        'productParentCode' => $product_order->product->parent_code ?? null,
                        'productCode' => $product_order->product->code ?? null,
                        'productOrder' => $product_order->order_id,
                        'productName' => $product_order->product->full_name_clear ?? null,
                        'productColor' => $product_order->product->color_id,
                        'productColorName' => $product_order->product->color->name ?? '',
                        'productSize' => $product_order->product->size_id,
                        'productSizeName' => $product_order->product->size->name ?? '',
                        'productSizeSort' => $product_order->product->size->sort ?? 0,
                        'productQuantity' => $product_order->quantity,
                        'productPriceWithoutTax' => $product_order->price_without_tax,
                        'isService' => !$product_order->product->parent_id ? true : false,
                        'customerId' => $order->user_id ?? null,
                        'customer' => $order->user_name_clear ?? null,
                    ]);
                }
            }

            $this->orderCollection = $ordercollection->toArray();
            
            $this->productsCollection = $productsCollection;

            $this->products = $productsCollection->groupBy(['productParentId', function (array $item) {
                return $item['productParentName'].' - '.$item['productColorName'].' => '.$item['productParentCode'];
            }], $preserveKeys = true);

        }

        return view('backend.order.print-export-orders', [
            'ordercollection' => $this->orderCollection,
            'productsGrouped' => $this->products ? $this->products() : null,
        ]);
    }
}

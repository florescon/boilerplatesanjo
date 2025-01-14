<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;

class PrintExportByDate extends Component
{
    public $orders;

    public $dateInput;
    public $dateOutput;

    public $summary;

    public $isProduct;
    public $isService;

    public $orderCollection;

    public $productsCollection;

    public bool $width = false;

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

        if($this->dateOutput){

            $dateInput = now();

            $ordersJson = Order::whereBetween('created_at', [$this->dateInput.' 00:00:00', $this->dateOutput.' 23:59:59'])->onlyOrders()->outFromStore()->with('products', 'user.customer')->get();

            foreach($ordersJson as $order){

                $ordercollection->push([
                    'id' => $order->id,
                    'folio' => $order->folio,
                    'user' => $order->user_name_clear ?? null,
                    'userId' => $order->user_id ?? null,
                    'comment' => $order->comment,
                    'request' => $order->request,
                ]);

                foreach($order->products as $product_order){

                    if($this->isProduct && $product_order->product->isProduct()){
                        $productsCollection->push([
                            'productId' => $product_order->id,
                            'productParentId' => $product_order->product->parent_id ?? $product_order->product_id,
                            'productParentName' => $product_order->product->only_name ?? null,
                            'productParentCode' => $product_order->product->parent_code ?? null,
                            'productCode' => $product_order->product->code ?? null,
                            'productOrder' => $order->folio,
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

                    if($this->isService && !$product_order->product->isProduct()){
                        $productsCollection->push([
                            'productId' => $product_order->id,
                            'productParentId' => $product_order->product->parent_id ?? $product_order->product_id,
                            'productParentName' => $product_order->product->only_name ?? null,
                            'productParentCode' => $product_order->product->parent_code ?? null,
                            'productCode' => $product_order->product->code ?? null,
                            'productOrder' => $order->folio,
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
            }

            $this->orderCollection = $ordercollection->toArray();
            
            $this->productsCollection = $productsCollection;

            $this->products = $productsCollection->groupBy(['productParentId', function (array $item) {
                return $item['productParentName'].' - '.$item['productColorName'].' => '.$item['productParentCode'];
            }], $preserveKeys = true);


        }

        return view('backend.order.print-export-by-date', [
            'ordercollection' => $this->orderCollection,
            'productsGrouped' => $this->products ? $this->products() : null,
        ]);
    }
}

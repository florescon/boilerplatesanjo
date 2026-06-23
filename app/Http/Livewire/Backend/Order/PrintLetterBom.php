<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use DB;

class PrintLetterBom extends Component
{
    public Order $order;    

    public bool $width = TRUE;
    public bool $prices = false;
    public bool $priceIVaIncluded = false;
    public bool $breakdown = false;
    public bool $general = false;
    public bool $details = true;
    public bool $actualStock = TRUE;

    protected $queryString = [
        'width' => ['except' => FALSE],
        'prices' => ['except' => FALSE],
        'priceIVaIncluded' => ['except' => FALSE],
        'breakdown' => ['except' => FALSE],
        'general' => ['except' => FALSE],
        'details' => ['except' => FALSE],
    ];

    public function render()
    {
        $orderServices = DB::table('product_order as a')
                ->selectRaw('
                    b.name as product_name,
                    null as product_code,
                    null as color_name,
                    null as size_name,
                    null as brand_name,
                    min(a.price) as min_price,
                    max(a.price) as max_price,
                    min(a.price) <> max(a.price) as omg,
                    sum(a.quantity) as sum,
                    sum(a.quantity * a.price) as sum_total,
                    a.quantity as total_by_product
                ')
                ->join('products as b', 'a.product_id', '=', 'b.id')
                ->where('order_id', $this->order->id)
                ->where('b.type', '=', 0)
                ->groupBy('b.id', 'a.price')
                ;

        $orderGroup = DB::table('product_order as a')
            ->selectRaw('
                c.name as product_name,
                c.code as product_code,
                d.name as color_name,
                e.name as size_name,
                f.name as brand_name,
                min(a.price) as min_price,
                max(a.price) as max_price,
                min(a.price) <> max(a.price) as omg,
                sum(a.quantity) as sum,
                sum(a.quantity * a.price) as sum_total,
                count(*) as total_by_product
            ')
            ->join('products as b', 'a.product_id', '=', 'b.id')
            ->join('products as c', 'b.parent_id', '=', 'c.id')
            ->join('colors as d', 'b.color_id', '=', 'd.id')
            ->join('sizes as e', 'b.size_id', '=', 'e.id')
            ->join('brands as f', 'c.brand_id', '=', 'f.id')  // Agregamos el join con la tabla brands
            ->groupBy('b.parent_id', 'b.color_id', 'a.price')
            ->where('order_id', $this->order->id)
            ->orderBy('product_name')
            ->orderBy('color_name')
            ->union($orderServices)
            ->get();











        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();

        $orders = Order::where('id', $this->order->id)
        ->with(
            'products.consumption_filter.material',
            'products.parent',
            'products.order.user.customer'
        )
        ->get();

        foreach($orders as $order){

            $ordercollection->push([
                'id' => $order->id,
                'folio' => $order->folio,
                'user' => optional($order->user)->name,
                'type' => $order->characters_type_order,
                'comment' => $order->comment,
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

        $products = $productsCollection->groupBy(['productParentId', function ($item) {
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


        $ordercollection->toArray();

        $allMaterials = $materials->map(function ($product) {
        return [
            'order'            => $product['order'],
            'material_name' => $product['material_name'],
            'part_number'         => $product['part_number'],
            'unit_measurement' => $product['unit_measurement'],
            'quantity' => $product['quantity'],
            'family' => $product['family'],
            'stock' => $product['stock'],

            ];
        })->sortBy(['family', 'asc'],['material_name', 'asc']);



        // dd($orderGroup);

        // dd($this->order->products->toArray());

        // dd($this->order->getProductsGroupedByParentAndSize());

        $tablesData = $this->order->getSizeTablesData();

        return view('backend.order.livewire.print-letter-bom', [
            'tablesData' => $tablesData,
            'orderGroup' => $orderGroup,
            'allMaterials' => $allMaterials,
        ]);
    }
}

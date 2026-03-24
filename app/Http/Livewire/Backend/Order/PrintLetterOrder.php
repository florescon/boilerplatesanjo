<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\ProductionBatch;
use App\Models\ProductOrder;
use App\Models\Material;
use App\Models\MaterialOrder;

use DB;

class PrintLetterOrder extends Component
{
    public Order $order;
    public ProductionBatch $station;    

    public bool $width = TRUE;
    public bool $general = false;
    public bool $details = true;

    public $manualMaterials = [];

    public $add_qty = [];

    public $add_comment = [];

    protected $queryString = [
        'width' => ['except' => FALSE],
        'general' => ['except' => FALSE],
        'details' => ['except' => FALSE],
    ];


    public function mount()
    {
        $this->manualMaterials = MaterialOrder::where('order_id', $this->order->id)
            ->where('production_batch_id', $this->station->id)
            ->where('manual', true)
            ->get()
            ->groupBy('material_id')
            ->map(function ($items) {
                return $items->toArray(); // 🔥 convertir cada grupo
            })
            ->toArray(); // 🔥 convertir todo
    }

    public function show($id)
    {
        // $record = Product::withTrashed()->findOrFail($id);
        // $this->name = $record->name;
        // $this->code = $record->code;
        // $this->price = $record->price;
        // $this->is_active = $record->status_name;
        // $this->created = $record->created_at;
        // $this->updated = $record->updated_at;
    }


public function saveData($key)
{

    $this->validate(
        [
            "add_qty.$key" => 'required|numeric|min:0',
            "add_comment.$key" => 'nullable|string|max:200',
        ],
        [
            "add_qty.$key.required" => 'La cantidad es obligatoria.',
            "add_qty.$key.numeric" => 'La cantidad debe ser un número.',
        ],
        [
            "add_qty.$key" => 'cantidad',
            "add_comment.$key" => 'comentario',
        ]
    );

    $quantity = $this->add_qty[$key] ?? null;
    $add_comment = $this->add_comment[$key] ?? null;
    $select_material = $key ?? null;
    $order = $this->order->id;
    $station = $this->station->id;
    $manual = true;

    $material = Material::find($select_material);

    if ($material->stock < $quantity) {
        return $this->emit('swal:modal', [
            'icon' => 'error',
            'title' => 'No hay suficiente cantidad en el inventario',
        ]);
    }
    else{
       // 🔥 Guardar en DB
        MaterialOrder::create([
            'quantity' => $quantity,
            'comment' => $add_comment,
            'material_id' => $select_material,
            'order_id' => $order,
            'production_batch_id' => $station,
            'manual' => $manual,
        ]);

        // reset
        $this->add_qty[$key] = null;
        $this->add_comment[$key] = null;
    }
    return $this->redirectRoute('admin.order.letter_materia_prod', [$this->order->id, $this->station->id]);

}
    public function render()
    {

        $products = $this->station->items()->with('product.parent.size')->get();
        $parentIds = $products->pluck('product.parent_id')->filter()->unique()->values();


        // dd($products);

    $allPossibleSizes = [];
    if ($parentIds->isNotEmpty()) {
        $allPossibleSizes = DB::table('products')
            ->join('sizes', 'sizes.id', '=', 'products.size_id')
            ->whereIn('products.parent_id', $parentIds)
            ->select('products.parent_id', 'products.size_id', 'sizes.sort', 'sizes.name')
            ->distinct()
            ->orderBy('sizes.sort')
            ->get()
            ->groupBy('parent_id');
    }

    // Agrupar por parent_id primero
    $groupedByParent = $products->groupBy(function($item) {
        return $item->product->parent_id ?? 'no_parent';
    });

    $result = [];

    foreach ($groupedByParent as $parentId => $parentProducts) {

        // Obtener tallas únicas para este parent
        $currentSizes = $parentProducts->filter(fn($item) => $item->product->size_id)
            ->map(fn($item) => [
                'id' => $item->product->size_id,
                'sort' => $item->product->size->sort ?? $item->product->parent->size->sort ?? 0,
                'name' => $item->product->size->name ?? $item->product->parent->size->name ?? 'N/A',
            ])
            ->unique('id');

        // Tallas posibles para este parent (desde datos precargados)
        $possibleSizes = collect($allPossibleSizes[$parentId] ?? [])
            ->map(fn($size) => [
                'id' => $size->size_id,
                'sort' => $size->sort,
                'name' => $size->name,
            ]);

        // Combinar y ordenar
        $uniqueSizes = $currentSizes->merge($possibleSizes)
            ->unique('id')
            ->sortBy('sort')
            ->values();

        // Agrupar productos por nombre base para este parent
        $groupedProducts = [];
        foreach ($parentProducts as $item) {
            $fullName = $item->product->full_name_clear_sort;
            $baseName = preg_replace('/\s\d+$/', '', $fullName);

            $onlyName = $item->product->only_name;
            $baseNameOnly = preg_replace('/\s\d+$/', '', $onlyName);
            
            if (!isset($groupedProducts[$baseName])) {
                $groupedProducts[$baseName] = [
                    'name' => $baseNameOnly,
                    'color' => $item->product->parent_id ? $item->product->color_name_clear : '',
                    'color_id' => $item->product->parent_id ? $item->product->color_id : '',
                    'general_code' => $item->product->parent_id ? $item->product->parent->code : $item->product->name,
                    'items' => collect(),
                    'no_size' => null,
                    'no_size_items' => collect() // Nueva colección para almacenar todos los items sin talla
                ];
            }
            
            if ($item->product->size_id) {
                $sizeId = $item->product->size_id;

                
                // Si ya existe un producto con este size_id, sumamos las cantidades
                if (isset($groupedProducts[$baseName]['items'][$sizeId])) {
                    $existingItem = $groupedProducts[$baseName]['items'][$sizeId];
                    $existingItem->input_quantity += $item->input_quantity;
                    $groupedProducts[$baseName]['items'][$sizeId] = $existingItem;
                } else {
                    $groupedProducts[$baseName]['items'][$sizeId] = $item;
                }
                
            } else {
                // Almacenar todos los items sin talla en la colección
                $groupedProducts[$baseName]['no_size_items']->push($item);
            }
        }

        $parentName = $parentId === 'no_parent' 
            ? 'Servicios' 
            : ($parentProducts->first()->product->parent->name ?? 'Parent '.$parentId);

        $parentCode = $parentId === 'no_parent' 
            ? '' 
            : ($parentProducts->first()->product->parent->code ?? 'Parent '.$parentId);


        $result[$parentId] = [
            'parent_name' => $parentName,
            'parent_code' => $parentCode,
            'uniqueSizes' => $uniqueSizes,
            'groupedProducts' => $groupedProducts
        ];
    }



    $groupedData = $result;



    $sortedGroups = collect($groupedData)->sortBy(function($group) {
        return strtolower($group['parent_name']);
    });
    
    $tables = [];
    
    foreach ($sortedGroups as $parentId => $data) {
        $sortedRows = collect($data['groupedProducts'])->sortBy(function($product) {
            return strtolower($product['name']);
        })->values();
        
        $sizeTotals = [];
        foreach ($data['uniqueSizes'] as $size) {
            $sizeTotals[$size['id']] = [
                'input_quantity' => 0,
                'amount' => 0
            ];
        }
        $noSizeTotal = ['input_quantity' => 0, 'amount' => 0];
        $grandTotal = 0;
        $rowQuantity = 0;
        
        $preparedRows = $sortedRows->map(function($product) use ($data, &$sizeTotals, &$noSizeTotal, &$grandTotal, &$rowQuantity) {
            $row = [
                'name' => $product['name'],
                // 'product_id' => $product['product_id'],
                'general_code' => $product['general_code'],
                'color_product' => $product['color'] ?: 'N/A',
                'color_id' => $product['color_id'] ?: 'N/A',
                'sizes' => [],
                'no_size' => null,
                'row_total' => 0,
                'row_quantity' => 0
            ];
        
            // Procesar productos con talla
            foreach ($data['uniqueSizes'] as $size) {
                if (isset($product['items'][$size['id']])) {
                    $item = $product['items'][$size['id']];
                    $input_quantity = $item->input_quantity;
                    $amount = $input_quantity * $item->price;
                    
                    $row['sizes'][$size['id']] = [
                        'input_quantity' => $input_quantity,
                        'amount' => $amount,
                        'only_display' => $input_quantity,
                        'display' => "{$input_quantity} &nbsp; <small class='font-italic text-primary'>".priceWithoutIvaIncluded($item->price)."</small>"
                    ];
                    
                    $sizeTotals[$size['id']]['input_quantity'] += $input_quantity;
                    $sizeTotals[$size['id']]['amount'] += $amount;
                    $row['row_total'] += $amount;
                    $row['row_quantity'] += $input_quantity;
                }
            }
            
            // Procesar productos sin talla (ahora manejamos múltiples items)
            if ($product['no_size_items']->isNotEmpty()) {
                $input_quantity = 0;
                $amount = 0;
                $displayParts = [];
                
                foreach ($product['no_size_items'] as $item) {
                    $itemQuantity = $item->input_quantity;
                    $itemAmount = $itemQuantity * $item->price;
                    
                    $input_quantity += $itemQuantity;
                    $amount += $itemAmount;
                    
                    $displayParts[] = "{$itemQuantity} &nbsp; <small class='font-italic text-primary'>".priceWithoutIvaIncluded($item->price)."</small>";
                }
                
                $row['no_size'] = [
                    'input_quantity' => $input_quantity,
                    'amount' => $amount,
                    'only_display' => $input_quantity,
                    'display' => implode(' + ', $displayParts)
                ];
                
                $noSizeTotal['input_quantity'] += $input_quantity;
                $noSizeTotal['amount'] += $amount;
                $row['row_total'] += $amount;
                $row['row_quantity'] += $input_quantity;
            }
            
            $row['row_total_display'] = priceWithoutIvaIncluded($row['row_total']);
            $grandTotal += $row['row_total'];
            $rowQuantity += $row['row_quantity'];
            
            return $row;
        });
        
        // dd($preparedRows);

        $tables[$parentId] = [
            'parent_name' => $data['parent_name'],
            'parent_code' => $data['parent_code'],
            'headers' => $data['uniqueSizes']->map(fn($size) => [
                'id' => $size['id'],
                'name' => $size['name'],
                'sort' => $size['sort']
            ]),
            'rows' => $preparedRows->toArray(),
            'totals' => [
                'size_totals' => $sizeTotals,
                'no_size_total' => $noSizeTotal,
                'grand_total' => priceWithoutIvaIncluded($grandTotal),
                'row_quantity' => $rowQuantity,
            ]
        ];
    }
    

        // $tablesData = $this->order->getSizeTablesData();
        $tablesData = $tables;

        // dd($tablesData);






        $consumptionCollect = collect();
        $ordercollection = collect();

            $ordercollection->push([
                'id' => $this->order->id,
                'folio' => $this->order->folio,
                'user' => optional($this->order->user)->name,
                'type' => $this->order->characters_type_order,
                'comment' => $this->order->comment,
            ]);

            foreach($this->station->items as $product_statione){

                $productOrder = ProductOrder::where('product_id', $product_statione->product_id)->where('order_id', $this->station->order_id)->first();

                $quantity = $product_statione->input_quantity;

                if($productOrder->gettAllConsumptionSecond($quantity) != 'empty'){
                    foreach($productOrder->gettAllConsumptionSecond($quantity) as $key => $consumption){
                        $consumptionCollect->push([
                            'order' => $this->order->id,
                            'product_order_id' => $productOrder->id, 
                            'material_name' => $consumption['material'],
                            'part_number' => $consumption['part_number'],
                            'material_id' => $key,
                            'unit' => $consumption['unit'],
                            'unit_measurement' => $consumption['unit_measurement'],
                            'vendor' => $consumption['vendor'],
                            'family' => $consumption['family'],
                            'cloth_width' => $consumption['cloth_width'],
                            'quantity' => $consumption['quantity'],
                            'stock' => $consumption['stock'],
                        ]);
                    }
                }
            }


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
                'cloth_width' => $row[0]['cloth_width'],
                'quantity' => $row->sum('quantity'),
                'stock' => $row[0]['stock'],
            ];
        });


        $allMaterials = $materials->map(function ($product) {
            return [
                'order'            => $product['order'],
                'material_name' => $product['material_name'],
                'part_number'         => $product['part_number'],
                'unit_measurement' => $product['unit_measurement'],
                'material_id' => $product['material_id'],
                'cloth_width' => $product['cloth_width'],
                'quantity' => $product['quantity'],
                ];
        });

        return view('backend.order.livewire.print-letter-order', [
            'tablesData' => $tablesData,
            'allMaterials' => $allMaterials,
        ]);
    }
}

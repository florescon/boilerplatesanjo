<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Auth\Models\User;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Traits\Scope\OrderScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\OrderStatusPayment;
use App\Models\OrderStatusDelivery;
use App\Models\OrdersDelivery;
use App\Models\ProductStation;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class Order extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, OrderScope, Sluggable;

    protected $cascadeDeletes = ['product_order', 'product_sale', 'product_quotation', 'product_output', 'suborders', 'product_suborder', 'batches', 'materials_order', 'service_orders', 'stations'];

    protected $fillable = [
        'date_entered', 
        'cash_id',
        'user_departament_changed_at',
        'feedstock_changed_at',
        'user_id',
        'seller_id',
        'departament_id',
        'comment',
        'request',
        'purchase',
        'invoice',
        'branch_id',
        'type',
        'created_at',
        'folio',
        'from_quotation',
        'quotation',
        'flowchart',
        'complementary',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'total_payments',
        'total_payments_remaining',
        'payment_label',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_entered' => 'date',
        'automatic_production' => 'boolean',
        'from_store' => 'boolean',
        'approved' => 'boolean',
        'user_departament_changed_at' => 'datetime',
        'feedstock_changed_at' => 'datetime',
        'to_stock' => 'boolean',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'full_slug',
                'onUpdate' => false,
            ]
        ];
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date_entered',
        'user_departament_changed_at',
        'feedstock_changed_at',
    ];

    public function getFullSlugAttribute(): string
    {
        return 'sju'. ' ' . Str::random(6);
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function departament()
    {
        return $this->belongsTo(Departament::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id')->withTrashed();
    }

    /**
     * Return the correct order status formatted.
     *
     * @return mixed
     */
    public function getUserNameAttribute(): string
    {
        if($this->user_id){
            return $this->user->real_name;
        }
        elseif($this->departament_id){
           return $this->departament->name;
        }
        elseif($this->isFromStore()){
            return "<span class='badge badge-primary'>General".'</span>';
        }

        return "<span class='badge badge-primary'>".__('Internal control').'</span>';
    }

    public function getUserDetailsAttribute(): string
    {
        if($this->user_id){
            return $this->user->details;
        }
        elseif($this->departament_id){
           return $this->departament->name;
        }

        return '';
    }

    /**
     * Return the correct order status formatted.
     *
     * @return mixed
     */
    public function getUserNameClearAttribute(): string
    {
        if($this->user_id){
            return $this->user->real_name;
        }
        elseif($this->departament_id){
           return $this->departament->name;
        }
        elseif($this->isFromStore()){
            return __('General');
        }

        return __('Internal control');
    }

    public function getTrackingNumberAttribute(): ?string
    {
        return $this->slug ?? '';
    }

    /**
     * @return mixed
     */
    public function batches()
    {
        return $this->hasMany(Batch::class)->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function batches_main()
    {
        return $this->hasMany(Batch::class, 'order_id')->where('batch_id', NULL)->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function batches_products()
    {
        return $this->hasMany(BatchProduct::class)->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function stations()
    {
        return $this->hasMany(Station::class)->orderBy('created_at', 'desc');
    }

    public function stations_products()
    {
        return $this->hasMany(ProductStation::class)->orderBy('created_at', 'desc');
    }

    public function stations_products_without_not_restricted()
    {
        return $this->hasMany(ProductStation::class)
                    ->whereHas('status', function ($query) {
                        $query->where('not_restricted', false);
                    })
                    ->orderBy('created_at', 'desc');
    }

    public function initialLot()
    {
        return Status::where('initial_lot', true)->first();
    }

    public function initialProcess()
    {
        return Status::where('initial_process', true)->first();
    }

    public function getLastProcess()
    {
        return Status::lastStatusProcess();  
    }

    public function lot_stations()
    {
        return $this->hasMany(Station::class)->where('status_id', $this->initialLot()->id)->orderBy('created_at', 'desc');
    }

    public function process_stations()
    {
        return $this->hasMany(Station::class)->where('status_id', $this->initialProcess()->id)->orderBy('created_at', 'desc');
    }

    public function getTotalStationsAttribute(): int
    {
        return $this->stations_products_without_not_restricted->sum(function($product) {
          return $product->metadata['open'] + $product->metadata['closed'];
        });
    }

    public function getTotalBatchAttribute(): int
    {
        return $this->batches_main->sum(function($batches) {
          return $batches->batch_product->sum('quantity');
        });
    }

    public function getTotalBatchPendingAttribute(): int
    {
        return $this->total_products - $this->total_batch;
    }


    public function getStatuses($restricted = false)
    {
        return DB::table('statuses')
            ->where(function($query) {
                $query->where('batch', true)
                      ->orWhere('supplier', true)
                      ->orWhere('process', true);
            })
            ->whereNull('deleted_at')
            ->where('not_restricted', $restricted ? 1 : 0)
            ->pluck('id', 'short_name');
    }


    public function getStatusesStation()
    {
        return DB::table('statuses')->where('batch', TRUE)->orWhere('process', TRUE)->where('deleted_at', NULL)->pluck('id','short_name');
    }

    public function getTotalGraphicAttribute()
    {
        $totals = DB::table('batch_products')
            ->where('order_id', $this->id)
            ->where('deleted_at', NULL)
            ->where('active', '>', 0);

            foreach ($this->getStatuses() as $key => $status) {
                $totals->selectRaw("SUM(CASE WHEN status_id = $status THEN active END) as $key");
            }

            $related = $totals->first();

            $difference = $this->total_products - $this->total_batch;

            $collection = collect($related);
            $collection->prepend("$difference", 'captura');

            // return $collection->filter();
            return $collection;
    }



    public function getTotalGraphicNewAttribute()
    {
        $orderId = $this->id;

        $statuses = $this->getStatuses();
        $statusesRestricted = $this->getStatuses(true);

        $selects = $statuses->map(function($status, $key) {
            return "SUM(CASE WHEN status_id = $status THEN JSON_EXTRACT(metadata, '$.open') + JSON_EXTRACT(metadata, '$.closed') END) as $key";
        })->implode(', ');

        $selectsExtra = $statusesRestricted->map(function($status, $key) {
            return "SUM(CASE WHEN status_id = $status THEN JSON_EXTRACT(metadata, '$.open') + JSON_EXTRACT(metadata, '$.closed') END) as $key";
        })->implode(', ');

        $totals = DB::table('product_stations')
            ->where('order_id', $orderId)
            ->whereNull('deleted_at')
            ->whereRaw("active > 0")
            ->selectRaw($selects)
            ->first();

        $totalsExtra = DB::table('product_stations')
            ->where('order_id', $orderId)
            ->whereNull('deleted_at')
            ->whereRaw("active > 0")
            ->selectRaw($selectsExtra)
            ->first();

        // Obtener la suma de 'out'
        $outTotal = DB::table('product_station_outs')
            ->where('order_id', $orderId)
            ->whereNull('deleted_at')
            ->sum('out_quantity');

        $difference = $this->total_products - $this->total_stations - $outTotal;

        $collection = collect($totals)->filter();
        $collectionExtra = collect($totalsExtra)->filter();

        if($difference < 0){
            $anotherDifference = $difference;
            $difference = 0;
            $collectionExtra->prepend(abs($anotherDifference), 'diferencia');
        }

        $collection->prepend($difference, 'captura');
        $collection->put('salida', $outTotal);

        // Mapa de colores
        $colors = [
            'captura' => '#EEEEEE',
            'salida' => '#8e5ea2',
            'corte' => '#3ABEF9',
            'confeccion' => '#3572EF',
            'personalizacion' => '#E1AFD1',
            'proveedor' => '#FFFF80',
            'conformado' => '#FFE6E6',
            'embarque' => '#AD88C6',
            'calidad' => '#050C9C',
            'entrega' => '#7469B6',
            'diferencia' => '#49e82b',
        ];

        return ['collection' => $collection, 'collectionExtra' => $collectionExtra, 'colors' => $colors];
    }

    public function getTotalByStationAttribute()
    {
        $totals = DB::table('product_stations')
            ->where('order_id', $this->id)
            ->where('deleted_at', NULL)
            ->where('active', '=', 1);

            foreach ($this->getStatusesStation() as $key => $status) {
                $totals->selectRaw("SUM(CASE WHEN status_id = $status THEN quantity END) as $key");
            }

            $related = $totals->first();

            $difference = $this->total_products - $this->total_batch;

            $collection = collect($related);
            $collection->prepend("$difference", 'captura');

            // return $collection->filter();
            return $collection;
    }

    public function getTotalQuantityByStation($status)
    {
        $totals = DB::table('product_stations')
            ->where('order_id', $this->id)
            ->where('status_id', $status)
            ->where('deleted_at', NULL)
            ->sum('quantity');

        return $totals;
    }

    public function getTotalQuantityByStationOpened($status)
    {
        $totals = DB::table('product_stations')
            ->where('order_id', $this->id)
            ->where('status_id', $status)
            ->where('active', '=', 1)
            ->where('deleted_at', NULL)
            ->sum('metadata->open');

        return $totals;
    }

    public function getTotalQuantityByStationClosed($status)
    {
        $totals = DB::table('product_stations')
            ->where('order_id', $this->id)
            ->where('status_id', $status)
            ->where('active', '=', 1)
            ->where('deleted_at', NULL)
            ->sum('metadata->closed');

        return $totals;
    }

    /**
     * @return mixed
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class)->orderBy('created_at', 'desc');
    }

    public function last_ticket()
    {
        return $this->hasOne(Ticket::class)->latestOfMany();
    }

    public function last_ticket_updated()
    {
        return $this->hasOne(Ticket::class)->latestOfMany()->where('updated_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function orders_delivery()
    {
        return $this->hasMany(OrdersDelivery::class)->with('user');
    }

    public function last_order_delivery()
    {
        return $this->hasOne(OrdersDelivery::class)->latestOfMany();
    }

    /**
     * @return mixed
     */
    public function orders_payments()
    {
        return $this->hasMany(Finance::class)->with('audi', 'payment');
    }

    /**
     * @return mixed
     */
    public function last_payment()
    {
        return $this->hasOne(Finance::class)->latestOfMany();
    }

    public function total_payments()
    {
        return $this->orders_payments->sum('amount');
    }

    /**
     * Return Total order price without shipping amount.
     */
    public function getTotalPaymentsAttribute(): string
    {
        return $this->total_payments();
    }

    /**
     * @return mixed
     */
    public function products()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size');
    }

    /**
     * @return mixed
     */
    public function product_order()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size')->where('type', 1);
    }

    /**
     * Obtiene los productos agrupados por nombre base y tallas
     * 
     * @return array
     */
    public function getProductsGroupedBySize(): array
    {
        // $products = $this->products->load('product.parent.size');
        $products = $this->products()->with('product.parent.size')->get();
            
        // Obtener todas las tallas únicas presentes en el pedido
 
        // dd($products->toArray());


        $uniqueSizes = $products->filter(fn($item) => $item->product->size_id)
            ->map(fn($item) => [
                'id' => $item->product->size_id,
                'sort' => $item->product->size->sort,
                'name' => $item->product->size->name
            ])
            ->unique('id')
            ->sortBy('sort')
            ->values();


        // Agrupar productos por nombre base
        $grouped = [];
        foreach ($products as $item) {
            $fullName = $item->product->full_name_clear_sort;
            $baseName = preg_replace('/\s\d+$/', '', $fullName);
            
            if (!isset($grouped[$baseName])) {
                $grouped[$baseName] = [
                    'name' => $baseName,
                    'general_code' => $item->product->parent_id ? $item->product->parent->code : $item->product->code,
                    'items' => collect(),
                    'no_size' => null
                ];
            }
            
            if ($item->product->size_id) {
                $grouped[$baseName]['items'][$item->product->size_id] = $item;
            } else {
                $grouped[$baseName]['no_size'] = $item;
            }
        }
        
        return [
            'groupedProducts' => $grouped,
            'uniqueSizes' => $uniqueSizes,
        ];

    }
    
public function getSizeTableData(): array
{
    $data = $this->getProductsGroupedBySize();
    
    return [
        'headers' => $data['uniqueSizes']->map(fn($size) => [
            'id' => $size['id'],
            'name' => $size['name'],
            'sort' => $size['sort']
        ]),
        'rows' => collect($data['groupedProducts'])->map(function($product) use ($data) {
            $row = [
                'name' => $product['name'],
                'general_code' => $product['general_code'],
                'sizes' => [],
                'no_size' => null
            ];
            
            foreach ($data['uniqueSizes'] as $size) {
                $row['sizes'][$size['id']] = $product['items'][$size['id']] ?? null;
            }
            
            if ($product['no_size']) {
                $row['no_size'] = [
                    'quantity' => $product['no_size']->quantity,
                    'price' => $product['no_size']->price
                ];
            }
            
            return $row;
        })->values()->toArray()
    ];
}


    /**
     * Obtiene los productos agrupados por nombre base y tallas
     * 
     * @return array
     */
public function getProductsGroupedByParentAndSize(): array
{
    $products = $this->products()->with('product.parent.size')->get();
        
    // Agrupar por parent_id primero
    $groupedByParent = $products->groupBy(function($item) {
        return $item->product->parent_id ?? 'no_parent';
    });

    $result = [];

    foreach ($groupedByParent as $parentId => $parentProducts) {
        // Obtener tallas únicas para este parent
        $uniqueSizes = $parentProducts->filter(fn($item) => $item->product->size_id)
            ->map(fn($item) => [
                'id' => $item->product->size_id,
                'sort' => $item->product->size->sort ?? $item->product->parent->size->sort ?? 0,
                'name' => $item->product->size->name ?? $item->product->parent->size->name ?? $item->product->size_id
            ])
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
                    $existingItem->quantity += $item->quantity;
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

    return $result;
}


public function getSizeTableGroupedData(): array
{
    $groupedData = $this->getProductsGroupedByParentAndSize();
    
    $sortedGroups = collect($groupedData)->sortBy(function($group) {
        return strtolower($group['parent_name']);
    });

    $tables = [];
    
    foreach ($sortedGroups as $parentId => $data) {
        $tables[$parentId] = [
            'parent_name' => $data['parent_name'],
            'headers' => $data['uniqueSizes']->map(fn($size) => [
                'id' => $size['id'],
                'name' => $size['name'],
                'sort' => $size['sort']
            ]),
            'rows' => collect($data['groupedProducts'])->map(function($product) use ($data) {
                $row = [
                    'name' => $product['name'],
                    'color' => $product['color'],
                    'general_code' => $product['general_code'],
                    'sizes' => [],
                    'no_size' => null
                ];
                
                foreach ($data['uniqueSizes'] as $size) {
                    $row['sizes'][$size['id']] = $product['items'][$size['id']] ?? null;
                }
                
                if ($product['no_size']) {
                    $row['no_size'] = [
                        'color' => 'N/A',
                        'quantity' => $product['no_size']->quantity,
                        'price' => $product['no_size']->price
                    ];
                }
                
                return $row;
            })->values()->toArray()
        ];
    }
    
    return $tables;
}



public function getSizeTablesData(): array
{
    $groupedData = $this->getProductsGroupedByParentAndSize();
    
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
                'quantity' => 0,
                'amount' => 0
            ];
        }
        $noSizeTotal = ['quantity' => 0, 'amount' => 0];
        $grandTotal = 0;
        $rowQuantity = 0;
        
        $preparedRows = $sortedRows->map(function($product) use ($data, &$sizeTotals, &$noSizeTotal, &$grandTotal, &$rowQuantity) {
            $row = [
                'name' => $product['name'],
                'general_code' => $product['general_code'],
                'color_product' => $product['color'] ?: 'N/A',
                'sizes' => [],
                'no_size' => null,
                'row_total' => 0,
                'row_quantity' => 0
            ];
            
            // Procesar productos con talla
            foreach ($data['uniqueSizes'] as $size) {
                if (isset($product['items'][$size['id']])) {
                    $item = $product['items'][$size['id']];
                    $quantity = $item->quantity;
                    $amount = $quantity * $item->price;
                    
                    $row['sizes'][$size['id']] = [
                        'quantity' => $quantity,
                        'amount' => $amount,
                        'only_display' => $quantity,
                        'display' => "{$quantity} &nbsp; <small class='font-italic text-primary'>".priceWithoutIvaIncluded($item->price)."</small>"
                    ];
                    
                    $sizeTotals[$size['id']]['quantity'] += $quantity;
                    $sizeTotals[$size['id']]['amount'] += $amount;
                    $row['row_total'] += $amount;
                    $row['row_quantity'] += $quantity;
                }
            }
            
            // Procesar productos sin talla (ahora manejamos múltiples items)
            if ($product['no_size_items']->isNotEmpty()) {
                $quantity = 0;
                $amount = 0;
                $displayParts = [];
                
                foreach ($product['no_size_items'] as $item) {
                    $itemQuantity = $item->quantity;
                    $itemAmount = $itemQuantity * $item->price;
                    
                    $quantity += $itemQuantity;
                    $amount += $itemAmount;
                    
                    $displayParts[] = "{$itemQuantity} &nbsp; <small class='font-italic text-primary'>".priceWithoutIvaIncluded($item->price)."</small>";
                }
                
                $row['no_size'] = [
                    'quantity' => $quantity,
                    'amount' => $amount,
                    'only_display' => $quantity,
                    'display' => implode(' + ', $displayParts)
                ];
                
                $noSizeTotal['quantity'] += $quantity;
                $noSizeTotal['amount'] += $amount;
                $row['row_total'] += $amount;
                $row['row_quantity'] += $quantity;
            }
            
            $row['row_total_display'] = priceWithoutIvaIncluded($row['row_total']);
            $grandTotal += $row['row_total'];
            $rowQuantity += $row['row_quantity'];
            
            return $row;
        });
        
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
    
    return $tables;
}
    /**
     * @return mixed
     */
    public function product_sale()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size')->where('type', 2);
    }

    /**
     * @return mixed
     */
    public function product_request()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size')->where('type', 5);
    }

    /**
     * @return mixed
     */
    public function product_quotation()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size')->where('type', 6);
    }

    /**
     * @return mixed
     */
    public function product_output()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size')->where('type', 7);
    }

    /**
     * @return mixed
     */
    public function product_suborder()
    {
        return $this->hasMany(ProductOrder::class, 'suborder_id')->with('parent_order.product.parent', 'parent_order.product.color', 'parent_order.product.size');
    }

    /**
     * @return mixed
     */
    public function suborders()
    {
        return $this->hasMany(self::class, 'parent_order_id')->orderBy('created_at', 'desc');
    }

    /**
     * @return bool
     */
    public function isChildren(): ?bool
    {
        return $this->parent_order_id;
    }

    /**
     * @return bool
     */
    public function isNullUserDepartament(): ?bool
    {
        return !$this->user_id && !$this->departament_id;
    }

    /**
     * @return bool
     */
    public function getExistUserDepartamentAttribute(): ?bool
    {
        return $this->isNullUserDepartament();
    }

    public function getParentOrderAttribute(): ?string
    {
        if ($this->isChildren()) {
            return $this->parent_order_id ?? '';
        }

        return '';
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function getPaymentMethodAttribute(): ?string
    {
        if ($this->payment_method_id !== null) {
            return $this->payment->short_title ?? '-- '.__('undefined payment').' --';
        }

        return '-- '.__('undefined payment').' --';
    }

    /**
     * @return mixed
     */
    public function status_order()
    {
        return $this->hasMany(StatusOrder::class)->with('status');
    }

    public function last_status_order()
    {
        return $this->hasOne(StatusOrder::class)->latestOfMany();
    }

    public function getLastOrderByTypeAndBranchAttribute(): int
    {   
        if($this->type){
            $order = DB::table('orders')->where('branch_id', $this->branch_id)->where('type', $this->type)->latest()->first();
            return $order->folio ?: $this->id;
        }

        return $this->id;
    }

    /*public function getLastOrderByTypeAndBranchSkipAttribute(): int
    {   
        if($this->type != 6){

            switch( $this->type){
                case 11: 
                    $order = DB::table('orders')->where('branch_id', $this->branch_id ?? 0)->where('from_quotation', true)->latest()->skip(1)->first();
                default:
                    $order = DB::table('orders')->where('branch_id', $this->branch_id ?? 0)->where('type', $this->type ?? 1)->latest()->skip(1)->first();
            }

            return $order ? ($order->folio ?: $this->id) : $this->id;
        }

        else{

            $order = DB::table('orders')->where('branch_id', $this->branch_id ?? 0)->where('from_quotation', true)->latest()->skip(1)->first();

            return $order ? ($order->quotation ?: $this->id): $this->id;
        }

        return $this->id;
    }*/


    public function getLastOrderByTypeAndBranchSkipAttribute()
    {   
        // Determine the field to check for the last consecutive number
        $field = $this->type === 6 ? 'quotation' : 'folio';

        // Get the last order for the given type and branch_id
        
        if($this->type === 6){
            $lastOrder = self::where('branch_id', $this->branch_id ?? 0)
                ->orderBy($field, 'desc')
                ->first();
        }
        else{
        $lastOrder = self::where('type', $this->type)
            ->where('branch_id', $this->branch_id ?? 0)
            ->orderBy($field, 'desc')
            ->first();
        }

        // Return the next consecutive number
        return $lastOrder ? $lastOrder->$field + 1 : 1;
    }


    public function getLastOrderOrRequestAttribute(): int
    {   
        if($this->type){
            $order = DB::table('orders')->where('branch_id', $this->branch_id)->where('type', !$this->from_store ? 1 : 5)->latest()->first();
            if($order){
                return $order->folio ?: $this->id;
            }
            else{
                return $this->id;
            }
        }

        return $this->id;
    }

    public function getFolioOrIDAttribute()
    {
        if($this->folio !== 0)
            return '<strong style="color:#000;">'.$this->folio.'.</strong>';

        return '<strong style="color:#000;">'.$this->id.'.</strong>';
    }

    public function getFolioOrIDClearAttribute()
    {
        if($this->folio !== 0)
            return $this->folio;

        return $this->id;
    }

    public function last_status()
    {
        return DB::table('statuses')->latest('level')->where('deleted_at', NULL)->first();
    }

    public function previousOrder()
    {
        return self::where('id', '<', $this->id)->where('type', 1)->orderBy('id','desc')->first();
    }

    public function nextOrder()
    {
        return self::where('id', '>', $this->id)->where('type', 1)->orderBy('id','asc')->first();
    }

    public function getLastStatusOrderIdAttribute()
    {
        if (!$this->parent_order_id && $this->type != 2) {
            if(!$this->last_status_order){
                return null;
            }

            return $this->last_status_order ? $this->last_status_order->status->id : '';
        }

        return null;
    }

    public function getLastStatusOrderLabelAttribute()
    {
        if (!$this->parent_order_id && $this->type != 2) {
            if(!$this->last_status_order){
                return "<span class='badge badge-secondary'>".__('undefined').'</span>';
            }

            return '<strong>'.$this->last_status_order->name_status.'</strong> <br>'.$this->last_status_order->date_entered_or_created;
        }

        return "";
    }


    public function getAdvancedOrderLabelAttribute()
    {
        if (!$this->parent_order_id && ($this->type != 6 && $this->type != 7)) {
            return '$'. number_format((float)$this->total_payments, 2, '.', '');
        }

        return "N/A";
    }

    public function getRemainingOrderLabelAttribute()
    {
        if (!$this->parent_order_id && ($this->type != 6 && $this->type != 7)) {
            return '$'. number_format((float)$this->total_payments_remaining, 2, '.', '');
        }

        return "N/A";
    }

    public function getLastStatusOrderPercentageAttribute(): int
    {
            if(!$this->last_status_order){
                return 0;
            }

            return $this->last_status_order->percentage_status;
    }

    public function getLastStatusOrderColorAttribute()
    {
            if(!$this->last_status_order){
                return null;
            }

            return $this->last_status_order->color_status;
    }

    /**
     * @return mixed
     */
    public function materials_order()
    {
        return $this->hasMany(MaterialOrder::class)->with('material');
    }

    /**
     * @return mixed
     */
    public function service_orders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function getTotalProductsByAllAttribute(): int
    {
        return $this->products->sum('quantity');
    }

    public function getTotalProductsByAllProductsAttribute(): int
    {
        return $this->products->filter(function ($productOrder) {
            return $productOrder->product->isProduct();
        })->sum('quantity');
    }

    public function getTotalProductsByAllServicesAttribute(): int
    {
        return $this->products->filter(function ($productOrder) {
            return !$productOrder->product->isProduct();
        })->sum('quantity');
    }

    public function getTotalProductsAndServicesLabelAttribute()
    {
        return '<strong>Productos: </strong> <strong class="cs-accent_color">'.$this->total_products_by_all_products.
               '</strong><br> <strong>Servicios: </strong> <strong class="cs-accent_color">'.$this->total_products_by_all_services."</strong>";
    }

    public function getTotalProductsAndServicesLineLabelAttribute()
    {
        return '<strong>Productos: </strong> <strong class="text-primary">'.$this->total_products_by_all_products.
               '</strong> <strong>Servicios: </strong> <strong class="text-primary">'.$this->total_products_by_all_services."</strong>";
    }

    public function getTotalProductsAttribute(): int
    {
        return $this->product_order->sum('quantity');
    }

    public function getTotalProductsSaleAttribute(): int
    {
        return $this->product_sale->sum('quantity');
    }

    public function getTotalProductsRequestAttribute(): int
    {
        return $this->product_request->sum('quantity');
    }

    public function getTotalProductsQuotationAttribute(): int
    {
        return $this->product_quotation->sum('quantity');
    }

    public function getTotalProductsOutputAttribute(): int
    {
        return $this->product_output->sum('quantity');
    }

    public function getTotalProductsAssignmentsAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity - $parent->assignments->where('output', 0)->sum('quantity');
        });
    }

    public function getTotalByAllAttribute()
    {
        //1160
        return $this->products->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getSubtotalByAllAttribute()
    {
        //1000
        $subtotal = $this->products->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });

        return priceWithoutIvaIncludedNon($subtotal);
    }

    public function getCalculateDiscountAllAttribute()
    {
        //100
        return $this->discount > 0 ? (($this->discount * $this->subtotal_by_all) / 100) : 0;
    }

    public function getSubtotalLessDiscountAttribute()
    {
        //900
        return $this->subtotal_by_all - $this->calculate_discount_all;        
    }

    public function getTotalByAllWithDiscountAttribute()
    {
        //
        return $this->subtotal_less_discount + calculateIvaNon($this->subtotal_less_discount);
    }

    public function calculateDiscount($total)
    {
        return $this->discount > 0 ? ($this->discount * $total) / 100 : $calculate;
    }

    /**
     * Return Total order price without shipping amount.
     */
    public function getTotalPaymentsRemainingAttribute(): string
    {
        return $this->total_by_all_with_discount - $this->total_payments();
    }

    public function filterByDiscount(): bool
    {
        $limit = $this->created_at->addHours(1);
        $now = Carbon::now();

        switch ($this->type) {
            case 2:
                return !$now->gt($limit) ? true : false;
            case 3:
                return !$now->gt($limit) ? true : false;
            case 4:
                return false;
            case 5:
                return !$now->gt($limit) ? true : false;
            case 6:
                return true;
            case 7:
                return false;
            default:
                return !$now->gt($limit) ? true : false;
        }

        return false;
    }

    /**
     * Return payment label.
     */
    public function getPaymentLabelAttribute()
    {
        if($this->orders_payments()->exists()){
            if($this->total_payments_remaining <= 1){
                return "<span class='badge badge-success'>".__(OrderStatusPayment::PAID).'</span>';
            }
            else{
                return "<span class='badge badge-warning text-white'>".__(OrderStatusPayment::ADVANCED).'</span>';
            }
        }
        else{
            return "<span class='badge badge-danger'>".__('Payment').' '.__(OrderStatusPayment::PENDING).'</span>';
        }
    }


    public function getPaymentBoolAttribute() :bool
    {
        if($this->orders_payments()->exists()){
            if($this->total_payments_remaining <= 1){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function getDetailsForBoxAttribute()
    {
        return '<em>'.__('Articles').': '.$this->total_products_by_all.'</em> -- <strong>$'.$this->total_by_all.'</strong>';        
    }

    public function getTotalOrderAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getTotalSaleAttribute()
    {
        return $this->product_sale->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getTotalRequestAttribute()
    {
        return $this->product_request->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getTotalQuotationAttribute()
    {
        return $this->product_quotation->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getTotalSaleAndOrderAttribute(): string
    {
        return $this->total_sale + $this->total_order + $this->total_suborder + $this->total_request + $this->total_quotation;
    }

    public function getTotalArticlesAttribute(): string
    {
        return $this->total_products + $this->total_products_sale + $this->total_products_request + $this->total_products_suborder + $this->total_products_quotation + $this->total_products_output;
    }

    public function getTotalProductsSuborderAttribute(): int
    {
        return $this->product_suborder->sum(function($product) {
          return $product->quantity;
        });
    }

    public function getTotalSuborderAttribute()
    {
        return $this->product_suborder->sum(function($product) {
          return $product->quantity * ($product->price ? $product->price : $product->price);
        });
    }

    public function getTotalProductsAllSubordersAttribute(): int
    {
        return $this->suborders->sum(function($suborders) {
          return $suborders->product_suborder->sum('quantity');
        });
    }

    public function getTotalAvailableByProduct($byID)
    {
        return $this->suborders->sum(function($suborders) use ($byID) {
          return $suborders->product_suborder->where('parent_product_id', $byID)->sum('quantity');
        });
    }

    public function areAllProductOrdersMatched(): bool
    {
        return $this->product_order->every(function ($productOrder) {
            return $productOrder->isQuantityMatched();
        });
    }

    public function areAllProductOrdersMatchedSendToStock(): bool
    {
        return $this->product_order->every(function ($productOrder) {
            return $productOrder->isQuantityMatchedSendToStock();
        });
    }

    public function areAllProductStationsZero(): bool
    {
        return $this->stations_products->every(function ($productStation) {
            return $productStation->metadata['closed'] == 0 && $productStation->metadata['open'] == 0;
        });
    }

    public function aboutOrder()
    {
        $imageUrl = asset('img/processing.gif');

        switch (true) {
            case ($this->areAllProductOrdersMatched() || $this->areAllProductOrdersMatchedSendToStock()) 
                && 
                $this->areAllProductStationsZero():
                return '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><g fill="none" stroke="#000" stroke-width="2" stroke-linejoin="round"><path fill="#FFF" d="M17.5 21h-11A1.5 1.5 0 0 1 5 19.5v-7c0-.83.67-1.5 1.5-1.5h11c.83 0 1.5.67 1.5 1.5v7c0 .83-.67 1.5-1.5 1.5Z"></path><path d="M8 11V7a4 4 0 1 1 8 0v4"></path><circle cx="12" cy="16" r=".25"></circle></g></svg>';
            case $this->stations()->exists():
                return '<img width="100" class="pr-4 mr-4" src="'. $imageUrl .'">';
            default:
                return '';
        }

        return '';
    }

    public function aboutOrderInfo()
    {
        switch (true) {
            case ($this->areAllProductOrdersMatched() || $this->areAllProductOrdersMatchedSendToStock())
                && 
                $this->areAllProductStationsZero():
                return 'Orden finalizada';
            case $this->stations()->exists():
                return '¡En proceso tu orden!';
            default:
                return 'Aún sin nada procesado';
        }

        return 'Aún sin nada procesado';
    }

    /**
     * @return string
     */
    public function getTypeOrderAttribute()
    {
            switch ($this->type) {
                case 2:
                    return "<span class='badge badge-success'>".__('Sale').'</span>';
                case 3:
                    return "<span class='badge badge-warning text-white'>".__('Mix').'</span>';
                case 4:
                    return "<span class='badge text-white' style='background-color: purple;'>".__('Output').'</span>';
                case 5:
                    return "<span class='badge text-white' style='background-color: #2eb85c;'>".__('Request').'</span>';
                case 6:
                    return "<span class='badge text-white' style='background-color: #2eb85c;'>".__('Quotation').'</span>';
                case 7:
                    return "<span class='badge text-white' style='background-color: #2eb85c;'>".__('Output Products').'</span>';
                default:
                    return "<span class='badge badge-primary'>".__('Order').'</span>';
            }

        return '';
    }

    /**
     * @return string
     */
    public function getCharactersTypeOrderAttribute()
    {
        switch ($this->type) {
            case 2:
                return 'VEN';
            case 3:
                return 'MIX';
            case 4:
                return 'OUT';
            case 5:
                return 'PED';
            case 6:
                return 'COT';
            case 7:
                return 'OUTP';
            default:
                return 'ORD';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getTypeOrderClearAttribute()
    {
            switch ($this->type) {
                case 2:
                    return __('Sale');
                case 3:
                    return __('Mix: Order/Sale');
                case 4:
                    return __('Output');
                case 5:
                    return __('Request');
                case 6:
                    return __('Quotation');
                case 7:
                    return __('Output Products');
                case 1:
                    return __('Order');
            }

        return '';
    }

    /**
     * @return string
     */
    public function getTypeOrderClassesAttribute()
    {
        switch ($this->type) {
            case 2:
                return 'background-color:#DEFFDF';
            case 3:
                return 'background-color: #FFFFDE';
            case 4:
                return 'background-color: #F7DEFF';
            case 5:
                return 'background-color: #FFDBD3';
            case 6:
                return 'background-color: #86FFCF';
            case 7:
                return 'background-color: #d5c5ff';
            default:
                return 'background-color: #DEE4FF';
        }

        return '';
    }

    public function isOrder(): bool
    {
        return $this->type === 1;
    }

    public function isSale(): bool
    {
        return $this->type === 2;
    }

    public function isMix(): bool
    {
        return $this->type === 3;
    }

    public function isSuborder(): bool
    {
        return $this->type === 4;
    }

    public function isRequest(): bool
    {
        return $this->type === 5;
    }

    public function isQuotation(): bool
    {
        return $this->type === 6;
    }

    public function isOutputProducts(): bool
    {
        return $this->type === 7;
    }

    /**
     * @return bool
     */
    public function isFromStore(): ?bool
    {
        return $this->from_store;
    }

    /**
     * @return bool
     */
    public function isCreatedByUser(): ?bool
    {
        return $this->user_id == $this->audi_id;
    }

    /**
     * @return string
     */
    public function  getFromStoreorUserLabelAttribute()
    {
        if ($this->isFromStore()) {
            return "<span class='badge badge-dark rounded-circle'>".'<i class="fas fa-store"></i>'.'</span>';
        }

        if($this->isCreatedByUser()){
            return "<span class='badge badge-dark'>".'<i class="fas fa-user"></i>'.'</span>';
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return string
     */
    public function getApprovedAlertAttribute()
    {
        if(!$this->isApproved()){
            return '<i class="fas fa-exclamation-triangle" style="color:red;"></i>';
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isToStock(): bool
    {
        return $this->to_stock;
    }

    /**
     * @return string
     */
    public function getToStockFinalAttribute()
    {
        if($this->isToStock()){
            return '<i class="cil-chevron-double-right" style="color:brown;"></i>';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getApprovedLabelAttribute()
    {
        if(!$this->parent_order_id){    
            if ($this->isApproved()) {
                return "<span class='badge badge-success'>".__('Approved').'</span>';
            }

            return "<span class='badge badge-danger'>".__('Pending').'</span>';
        }

        return "";
    }

    /**
     * Cashable.
     */
    public function cashes()
    {
        return $this->morphMany(Cashable::class, 'cashable');
    }

    /**
     * @return bool
     */
    public function isDeletedFeedstock(): bool
    {
        return $this->feedstock_changed_at !== null;
    }

    /**
     * @return bool
     */
    public function isUserOrDepartamentReasigned(): bool
    {
        return $this->user_departament_changed_at !== null;
    }

    public function getDateForHumansAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    public function getDateEnteredOrCreatedAttribute()
    {
        return !$this->date_entered ? $this->created_at->isoFormat('D, MMM, YY') : $this->date_entered->isoFormat('D, MMM, YY');
    }

    public function getDateDiffForHumansCreatedAttribute()
    {
        return $this->created_at->diffForHumans(null, false, false, 2);
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
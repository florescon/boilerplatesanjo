<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class ProductOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_order';

    protected $initialLot;
    protected $initialProcess;
    protected $supplierProcess;
    protected $lastProcess;

	protected $fillable = [
        'order_id', 
        'suborder_id', 
        'product_id', 
        'quantity', 
        'price', 
        'type', 
        'parent_product_id', 
        'comment', 
        'price_without_tax', 
        'product_order_id',
    ];

    /**
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function parent_order()
    {
        return $this->belongsTo(self::class, 'parent_product_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function suborder()
    {
        return $this->belongsTo(Order::class, 'suborder_id', 'id')->withTrashed();
    }

    /**
     * @return bool
     */
    public function isOrder()
    {
        return $this->order_id;
    }

    /**
     * @return bool
     */
    public function isSuborder()
    {
        return $this->suborder_id;
    }

    /**
     * @return bool
     */
    public function isSale()
    {
        return $this->type === 2;
    }

    /**
     * @return bool
     */
    public function isOutputProducts()
    {
        return $this->type === 7;
    }

    /**
     * @return string
     */
    public function getTypeOrderAttribute()
    {
        switch ($this->type) {
            case 1:
                return __('Order');
            case 2:
                return __('Sale');
        }

        return '';
    }

    /**
     * @return string
     */
    public function getTypeOrderLabelAttribute()
    {
        switch ($this->type) {
            case 1:
                return "<span class='badge badge-primary'>".__('Order').'</span>';
            case 2:
                return "<span class='badge badge-success'>".__('Sale').'</span>';
        }

        if($this->isSuborder()){
            return "<span class='badge text-white' style='background-color: purple;'>".__('Suborder').'</span>';
        }

        return "<span class='badge badge-secondary'>".__('undefined').'</span>';
    }

    /**
     * @return string
     */
    public function getNameOrderOrSuborderAttribute()
    {
        if($this->isOrder()){
            return $this->product->full_name_link;
        }

        if($this->isSuborder()){
            return $this->product->full_name_link;            
        }

        return '';
    }

    public function getPriceWithIvaAttribute()
    {
        return number_format($this->price + ((setting('iva') / 100) * $this->price), 2, '.', '');
    }

    /**
     * @return string
     */
    public function getPriceOrderOrSuborderAttribute()
    {
        if($this->isOrder()){
            return $this->price;
        }

        if($this->isSuborder()){
            return $this->price ? $this->price : $this->price;            
        }

        return '';
    }

    /**
     * @return string
     */
    public function getOrderOrSuborderLabelAttribute()
    {
        if($this->order_id){
            return $this->order->folio_or_id;
        }

        if($this->suborder_id){
            return $this->suborder->folio_or_id;            
        }

        return '';
    }

    public function getOrderOrSuborderAttribute()
    {
        if($this->order_id){
            return $this->order_id;
        }

        if($this->suborder_id){
            return $this->suborder_id;
        }

        return null;
    }
    
    /**
     * @return mixed
     */
    public function material()
    {
        return $this->hasMany(MaterialOrder::class);
    }

    /**
     * @return mixed
     */
    public function batch_product()
    {
        return $this->hasMany(BatchProduct::class, 'product_order_id');
    }

    public function getTotalByProductAttribute()
    {
        return $this->quantity * $this->price;
    }

    public function getTotalByProductWithIvaAttribute()
    {
        return $this->quantity * $this->price_with_iva;
    }

    public function getAvailableAssignmentsAttribute()
    {
        return $this->quantity - $this->assignments->where('output', 0)->sum('quantity');
    }

    public function getAvailableBatchAttribute()
    {
        return $this->quantity - $this->batch_product->where('status_id', 4)->sum('quantity');
    }

    public function getAssignProcessAttribute()
    {
        return $this->batch_product->where('status_id', 11)->sum('quantity');
    }



    /* Available Lot */

    /**
     * @return mixed
     */
    public function lot_product()
    {
        return $this->hasMany(ProductStation::class, 'product_order_id');
    }

    public function product_station_received()
    {
        return $this->hasMany(ProductStationReceived::class, 'product_order_id');
    }

    public function product_station_out()
    {
        return $this->hasMany(ProductStationOut::class, 'product_order_id');
    }

    public function isQuantityMatched() :bool
    {
        $totalReceivedQuantity = $this->product_station_received->where('status_id', $this->getLastProcess()->id)->sum('quantity');
        return $this->quantity == $totalReceivedQuantity;
    }

    public function isQuantityMatchedSendToStock() :bool
    {
        $totalReceivedQuantity = $this->product_station_received->where('status_id', $this->getInitialProcess()->id)->sum('quantity');
        return $this->quantity == $totalReceivedQuantity;
    }

    /* Available Initial Lot */
    public function getAvailableLotAttribute()
    {
        return $this->quantity - $this->sum_initial_lot - $this->sum_supplier - $this->sum_initial_process;
    }


    /* Available Initial Process */
    public function getAvailableProcessAttribute()
    {
        return $this->quantity - $this->sum_initial_process - $this->sum_supplier;
    }

    /* Available Supplier Process */
    public function getAvailableSupplierAttribute()
    {
        return $this->quantity - $this->sum_initial_lot - $this->sum_initial_process - $this->sum_supplier;
    }

    public function getSumInitialLotAttribute()
    {
        return $this->lot_product->where('status_id', $this->getInitialLot()->id)->where('product_station_id', null)->sum('quantity');
    }
    public function getSumInitialProcessAttribute()
    {
        return $this->lot_product->where('status_id', $this->getInitialProcess()->id)->sum('quantity');   
    }
    public function getSumSupplierAttribute()
    {
        return $this->lot_product->where('status_id', $this->getSupplierProcess()->id)->where('product_station_id', null)->sum('quantity');
    }

    public function getInitialLot()
    {
        if (!$this->initialLot) {
            $this->initialLot = Status::firstStatusBatch();
        }
        return $this->initialLot;
    }

    public function getInitialProcess()
    {
        if (!$this->initialProcess) {
            $this->initialProcess = Status::firstStatusProcess();
        }
        return $this->initialProcess;
    }

    public function getSupplierProcess()
    {
        if (!$this->supplierProcess) {
            $this->supplierProcess = Status::getSupplierStatus();
        }
        return $this->supplierProcess;
    }

    public function getLastProcess()
    {
        if(!$this->lastProcess){
            $this->lastProcess = Status::lastStatusProcess();  
        }

        return $this->lastProcess;
    }


    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function consumption_filter()
    {
        return $this->hasManyThrough(Consumption::class, Product::class, 'id', 'product_id', 'product_id', 'parent_id')->with('material');
    }

    public function consumption_filter_grouped()
    {
        $this->consumption_filter()->where('color_id', null);
    }



public function gettAllConsumptionUngrouped()
{
    if ($this->consumption_filter->isNotEmpty()) {
        $groups = collect();

        $conditions = [
            ['color_id', $this->parent->color_id],
            ['size_id', $this->parent->size_id],
            [null, null]
        ];

        foreach ($conditions as $condition) {
            $filtered = $this->consumption_filter;

            if ($condition[0] !== null) {
                $filtered = $filtered->where('color_id', $condition[0]);
            }

            if ($condition[1] !== null) {
                $filtered = $filtered->where('size_id', $condition[1]);
            } else {
                $filtered = $filtered->whereNull('color_id')->whereNull('size_id');
            }

            foreach ($filtered as $consumption) {
                $groups->push([
                    'material_id' => $consumption->material_id,
                    'material_part_number' => $consumption->material->part_number ?? null,
                    'material_name' => $consumption->material->full_name_clear ?? null,
                    'stock' => $consumption->material->stock,
                    'quantity' => $consumption->quantity * $this->quantity,
                    'unit' => $consumption->quantity,
                    'unit_measurement' => $consumption->material->unit_measurement ?? null,
                    'vendor' => $consumption->material->vendor->short_name ?? null,
                    'family' => $consumption->material->family->name ?? null,
                    'price' => $consumption->material->price ?? null,
                ]);
            }
        }

        return $groups;
    }

    return 'empty';
}

    /*
    public function gettAllConsumptionUngrouped()
    {
        if($this->consumption_filter->isNotEmpty()){

            $groups0 = new Collection;
            $groups2 = new Collection;
            $groups3 = new Collection;

            // $grouped = $this->consumption_filter;

            foreach ($this->consumption_filter->where('color_id', $this->parent->color_id) as $consumption) {
                $groups0->push([
                    'material_id' => $consumption->material_id,
                    'material_part_number' => $consumption->material->part_number ?? null,
                    'material_name' => $consumption->material->full_name_clear ?? null,
                    'stock' => $consumption->material->stock,
                    'quantity' => $consumption->quantity * $this->quantity,
                    'unit' => $consumption->quantity,
                    'unit_measurement' => $consumption->material->unit_measurement ?? null,
                    'vendor' => $consumption->material->vendor->short_name ?? null,
                    'family' => $consumption->material->family->name ?? null,
                    'price' => $consumption->material->price,
                ]);
            }

            foreach ($this->consumption_filter->where('size_id', $this->parent->size_id) as $consumption) {
                $groups2->push([
                    'material_id' => $consumption->material_id,
                    'material_part_number' => $consumption->material->part_number ?? null,
                    'material_name' => $consumption->material->full_name_clear ?? null,
                    'stock' => $consumption->material->stock,
                    'quantity' => $consumption->quantity * $this->quantity,
                    'unit' => $consumption->quantity,
                    'unit_measurement' => $consumption->material->unit_measurement ?? null,
                    'vendor' => $consumption->material->vendor->short_name ?? null,
                    'family' => $consumption->material->family->name ?? null,
                    'price' => $consumption->material->price ?? null,
                ]);
            }

            foreach ($this->consumption_filter->whereNull('color_id')->whereNull('size_id') as $consumption) {
                $groups3->push([
                    'material_id' => $consumption->material_id,
                    'material_part_number' => $consumption->material->part_number ?? null,
                    'material_name' => $consumption->material->full_name_clear ?? null,
                    'stock' => $consumption->material->stock,
                    'quantity' => $consumption->quantity * $this->quantity,
                    'unit' => $consumption->quantity,
                    'unit_measurement' => $consumption->material->unit_measurement ?? null,
                    'vendor' => $consumption->material->vendor->short_name ?? null,
                    'family' => $consumption->material->family->name ?? null,
                    'price' => $consumption->material->price ?? null,
                ]);
            }

            $groups = $groups0->concat($groups2)->concat($groups3);
            return $groups;
        }
        
        return 'empty';
    } */

    public function gettAllConsumption()
    {
        if($this->gettAllConsumptionUngrouped() != 'empty'){
            return $this->gettAllConsumptionUngrouped()
                    ->groupBy('material_id')
                    ->map(function ($item) {
                        return [
                            'material' => $item[0]['material_name'],
                            'part_number' => $item[0]['material_part_number'],
                            'price' => $item[0]['price'],
                            'unit' => $item->sum('unit'),
                            'unit_measurement' => $item[0]['unit_measurement'],
                            'vendor' => $item[0]['vendor'],
                            'family' => $item[0]['family'],
                            'quantity' => $item->sum('quantity'),
                            'stock' => $item[0]['stock'],
                        ];
                    }); 
        }

        return 'empty';                                                   
    }

    public function gettAllConsumptionUngroupedSecond($quantity)
    {
        if($this->consumption_filter->isEmpty()){
            return 'empty';
        }

        $groups = collect();

        foreach ($this->consumption_filter as $consumption) {
            $groupKey = null;

            if ($consumption->color_id === $this->parent->color_id) {
                $groupKey = 'color';
            } elseif ($consumption->size_id === $this->parent->size_id) {
                $groupKey = 'size';
            } elseif (is_null($consumption->color_id) && is_null($consumption->size_id)) {
                $groupKey = 'none';
            }

            if ($groupKey) {
                $groups->push([
                    'material_id' => $consumption->material_id,
                    'material_part_number' => $consumption->material->part_number ?? null,
                    'material_name' => $consumption->material->full_name_clear ?? null,
                    'stock' => $consumption->material->stock,
                    'quantity' => $consumption->quantity * $quantity,
                    'unit' => $consumption->quantity,
                    'unit_measurement' => $consumption->material->unit_measurement ?? null,
                    'vendor' => $consumption->material->vendor->short_name ?? null,
                    'family' => $consumption->material->family->name ?? null,
                    'price' => $consumption->material->price ?? null,
                ]);
            }
        }

        return $groups;
    }

    public function gettAllConsumptionSecond($quantity)
    {
        $consumptions = $this->gettAllConsumptionUngroupedSecond($quantity);

        if ($consumptions === 'empty') {
            return 'empty';
        }

        return $consumptions->groupBy('material_id')->map(function ($items) {
            $firstItem = $items->first();

            return [
                'material' => $firstItem['material_name'],
                'part_number' => $firstItem['material_part_number'],
                'price' => $firstItem['price'],
                'unit' => $items->sum('unit'),
                'unit_measurement' => $firstItem['unit_measurement'],
                'vendor' => $firstItem['vendor'],
                'family' => $firstItem['family'],
                'quantity' => $items->sum('quantity'),
                'stock' => $firstItem['stock'],
            ];
        });
    }

    /**
     * Get the product's.
     */
    // public function productSub()
    // {
    //     return $this->hasOneThrough(Product::class, ProductOrder::class);
    // }

    /**
     * Get all of the product order's assignments.
     */

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] =  str_replace(',', '', $value);
    }

    public function setPriceWithoutTaxAttribute($value)
    {
        $this->attributes['price_without_tax'] =  str_replace(',', '', $value);
    }

    public function assignments()
    {
        return $this->morphMany(Assignment::class, 'assignmentable');
    }
}
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

	protected $fillable = [
        'order_id', 'suborder_id', 'product_id', 'quantity', 'price', 'type', 'parent_product_id'
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

    public function getTotalByProductAttribute()
    {
        return $this->quantity * $this->price;
    }

    public function getAvailableAssignmentsAttribute()
    {
        return $this->quantity - $this->assignments->where('output', 0)->sum('quantity');
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
        if($this->consumption_filter->isNotEmpty()){

            $groups0 = new Collection;
            $groups2 = new Collection;
            $groups3 = new Collection;

            // $grouped = $this->consumption_filter;

            foreach ($this->consumption_filter->where('color_id', $this->parent->color_id) as $consumption) {
                $groups0->push([
                    'material_id' => $consumption->material_id,
                    'material_name' => $consumption->material->name,
                    'quantity' => $consumption->quantity * $this->quantity,
                    'unit' => $consumption->quantity,
                    'price' => $consumption->material->price,
                ]);
            }

            foreach ($this->consumption_filter->where('size_id', $this->parent->size_id) as $consumption) {
                $groups2->push([
                    'material_id' => $consumption->material_id,
                    'material_name' => $consumption->material->name,
                    'quantity' => $consumption->quantity * $this->quantity,
                    'unit' => $consumption->quantity,
                    'price' => $consumption->material->price,
                ]);
            }

            foreach ($this->consumption_filter->whereNull('color_id')->whereNull('size_id') as $consumption) {
                $groups3->push([
                    'material_id' => $consumption->material_id,
                    'material_name' => $consumption->material->name,
                    'quantity' => $consumption->quantity * $this->quantity,
                    'unit' => $consumption->quantity,
                    'price' => $consumption->material->price,
                ]);
            }

            $groups = $groups0->concat($groups2)->concat($groups3);
            return $groups;
        }
        
        return 'empty';
    }

    public function gettAllConsumption()
    {
        if($this->gettAllConsumptionUngrouped() != 'empty'){
            return $this->gettAllConsumptionUngrouped()
                            ->groupBy('material_id')
                            ->map(function ($item) {
                                return [
                                    'material' => $item[0]['material_name'],
                                    'price' => $item[0]['price'],
                                    'unit' => $item->sum('unit'),
                                    'quantity' => $item->sum('quantity'),
                                ];
                            }); 
        }

        return 'empty';                                                   
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
    public function assignments()
    {
        return $this->morphMany(Assignment::class, 'assignmentable');
    }
}
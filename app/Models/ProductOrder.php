<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_order';

	protected $fillable = [
        'order_id', 'suborder_id', 'product_id', 'quantity', 'price'
    ];

    /**
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return mixed
     */
    public function parent_order()
    {
        return $this->belongsTo(ProductOrder::class, 'product_id');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Auth\Models\User;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['product_order'];

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class)->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function product_order()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size');
    }

    /**
     * @return mixed
     */
    public function product_suborder()
    {
        return $this->hasMany(ProductOrder::class, 'suborder_id');
    }

    /**
     * @return mixed
     */
    public function suborders()
    {
        return $this->hasMany(Order::class, 'parent_order_id')->orderBy('created_at', 'desc');
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

    /**
     * @return mixed
     */
    public function materials_order()
    {
        return $this->hasMany(MaterialOrder::class)->with('material');
    }

    public function getTotalProductsAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity;
        });
    }

    public function getTotalProductsAssignmentsAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity - $parent->assignments->where('output', 0)->sum('quantity');
        });
    }

    public function getTotalOrderAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getTotalProductsSuborderAttribute()
    {
        return $this->product_suborder->sum(function($product) {
          return $product->quantity;
        });
    }

    public function getTotalProductsAllSubordersAttribute()
    {
        return $this->suborders->sum(function($suborders) {
          return $suborders->product_suborder->sum('quantity');
        });
    }

    public function getTotalAvailableByProduct($byID)
    {
        return $this->suborders->sum(function($suborders) use ($byID) {
          return $suborders->product_suborder->where('product_id', $byID)->sum('quantity');
        });
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
                default:
                    return "<span class='badge badge-primary'>".__('Order').'</span>';
            }

        return 'a';
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->approved;
    }

    /**
     * @return string
     */
    public function getApprovedLabelAttribute()
    {
        if ($this->isApproved()) {
            return "<span class='badge badge-success'>".__('Approved').'</span>';
        }

        return "<span class='badge badge-danger'>".__('Pending').'</span>';
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at->format('M, d Y');
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->updated_at->diffForHumans();
    }


}

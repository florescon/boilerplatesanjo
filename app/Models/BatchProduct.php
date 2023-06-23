<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class BatchProduct extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['received', 'children'];

    protected $fillable = [
        'order_id',
        'batch_id',
        'product_order_id',
        'product_id',
        'product_parent_id',
        'status_id',
        'personal_id',
        'quantity',
        'active',
        'comment',
        'audi_id',
        'batch_product_id',
        'from_stock',
    ];

    /**
     * @return mixed
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(self::class, 'batch_product_id');
    }

    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'batch_product_id')->withTrashed();
    }

    public function getQuantityChildrenAttribute()
    {
        return $this->children->sum('quantity');
    }

    /**
     * @return mixed
     */
    public function received()
    {
        return $this->hasMany(BatchProductReceive::class, 'batch_product_id');
    }

    public function getQuantityReceivedAttribute()
    {
        return $this->received->sum('quantity');
    }

    public function getDifferenceAttribute()
    {
        return $this->quantity - $this->quantity_received;        
    }

    public function getAvailableAttribute()
    {
        return $this->quantity_received - $this->quantity_children;
    }

    /**
     * @return mixed
     */
    public function personal()
    {
        return $this->belongsTo(User::class, 'personal_id')->withTrashed();
    }

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
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function status()
    {
        return $this->belongsTo(Status::class)->withTrashed();
    }
}

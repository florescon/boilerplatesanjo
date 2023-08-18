<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Domains\Auth\Models\User;
use App\Models\Traits\Scope\DateScope;

class ServiceOrder extends Model
{
    use HasFactory, SoftDeletes, DateScope, CascadeSoftDeletes;

    protected $table = 'service_orders';

    protected $cascadeDeletes = ['product_service_orders'];

    protected $fillable = [
        'order_id', 'user_id', 'authorized_id', 'created_id', 'image_id', 'branch_id', 'service_type_id', 'comment', 'dimensions', 'file_text', 'done', 'approved',
    ];

    /**
     * @return mixed
     */
    public function product_service_orders()
    {
        return $this->hasMany(ProductServiceOrder::class);
    }

    public function getTotalProductsAttribute(): int
    {
        return $this->product_service_orders->sum('quantity');
    }

    /**
     * @return mixed
     */
    public function service_type()
    {
        return $this->belongsTo(ServiceType::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function image()
    {
        return $this->belongsTo(Image::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function personal()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function authorized()
    {
        return $this->belongsTo(User::class, 'authorized_id')->withTrashed();
    }

    public function getIsDoneLabelAttribute()
    {
        return $this->done ? __('Done') : __('Pending');
    }

    /**
     * @return mixed
     */
    public function createdby()
    {
        return $this->belongsTo(User::class, 'created_id')->withTrashed();
    }

    public function getDateForHumansAttribute()
    {
        return $this->created_at->isoFormat('D, MMM, YY');
    }
}

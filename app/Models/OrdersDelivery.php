<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;

class OrdersDelivery extends Model
{
    use HasFactory;

    // public const TYPE_DELIVERED = 'delivered';
    // public const TYPE_PENDING = 'pending';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'audi_id',
    ];

    /**
     * @return string
     */
    public function getOrderDeliveryAttribute()
    {
            switch ($this->type) {
                case OrderStatusDelivery::PENDING:
                    return "<span class='badge text-dark' style='background-color: white;'>".__('Pending').'</span>';
                case OrderStatusDelivery::READY_FOR_DELIVERY:
                    return "<span class='badge badge-warning text-white'>".__('Ready for delivery').'</span>';
                case OrderStatusDelivery::DELIVERED:
                    return "<span class='badge badge-primary'>".__('Delivered').'</span>';
                default:
                    return "<span class='badge text-dark' style='background-color: white;'>".__('Pending').'</span>';
            }

        return '';
    }

    /**
     * Return the correct order delivery formatted.
     *
     * @return mixed
     */
    public function getFormattedTypeAttribute(): string
    {
        return OrderStatusDelivery::values()[$this->type];
    }

    /**
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }
}

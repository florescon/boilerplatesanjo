<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStationReceived extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_station_id',
        'product_order_id',
        'quantity',
        'comment',
        'status_id',
        'created_audi_id',
    ];

    public function product_station()
    {
        return $this->belongsTo(ProductStation::class);
    }

    /**
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function product_order()
    {
        return $this->belongsTo(ProductOrder::class)->withTrashed();
    }

    public function status()
    {
        return $this->belongsTo(Status::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'created_audi_id')->withTrashed();
    }

    public function getCreatedAtForHumansAttribute()
    {
        return $this->created_at->isoFormat('D, MMM, YYYY H:mm');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStationOut extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_station_id',
        'out_quantity',
        'type_out',
        'created_audi_id',
        'product_order_id',
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

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'created_audi_id')->withTrashed();
    }
}

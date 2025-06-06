<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'material_orders';

    protected $fillable = [
        'order_id', 'product_order_id', 'material_id', 'price', 'unit_quantity', 'quantity', 'audi_id', 'station_id', 'production_batch_id', 'manual'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'quantity_formatted',
    ];

    /**
     * Return formatted stock.
     */
    public function getQuantityFormattedAttribute(): string
    {
        return rtrim(rtrim(sprintf('%.8F', $this->quantity), '0'), ".");
    }

    /**
     * @return mixed
     */
    public function material()
    {
        return $this->belongsTo(Material::class)->with('unit', 'size', 'color')->withTrashed();
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
    public function product_order()
    {
        return $this->belongsTo(ProductOrder::class)->withTrashed();
    }

    public function station()
    {
        return $this->belongsTo(Station::class)->withTrashed();
    }

    public function production_batch()
    {
        return $this->belongsTo(ProductionBatch::class)->withTrashed();
    }

    public function getTotalByMaterialAttribute()
    {
        return $this->quantity * $this->price;
    }

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'manual' => 'boolean',
    ];
}

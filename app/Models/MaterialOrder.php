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
        'order_id', 'product_order_id', 'material_id', 'price', 'unit_quantity', 'quantity', 'audi_id' 
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
        return $this->belongsTo(Material::class);
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
    public function product_order()
    {
        return $this->belongsTo(ProductOrder::class);
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
        return $this->belongsTo(User::class, 'audi_id');
    }


}

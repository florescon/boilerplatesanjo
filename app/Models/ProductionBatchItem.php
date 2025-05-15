<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatchItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id', 
        'product_id', 
        'status_id',
        'input_quantity', 
        'output_quantity',
        'active',
        'is_principal',
        'with_previous',
        'status', 
        'current_station'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_principal' => 'boolean',
    ];

    
    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'batch_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class)->withTrashed();
    }

    public function getTotalProductsProdAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getAvailableQuantityAttribute()
    {
        // Si ya se completó el proceso
        if ($this->output_quantity == $this->input_quantity && $this->active == 0) {
            return 0;
        }

        // Calcula lo disponible, pero no menos de cero
        $available = $this->input_quantity - $this->output_quantity - $this->active;
        return abs($available);
        // return max(0, $available); // Retorna 0 si es negativo
    }

    public function product_order()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }
    
    public function logs()
    {
        return $this->hasMany(ProductionItemLog::class, 'batch_item_id');
    }

}

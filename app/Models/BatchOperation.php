<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'batch_id',
        'batch_item_id',
        'operation_id',
        'operation_name',
        'sequence',
        'expected',
        'processed',
        'received',
        'delivered',
        'status_name',
        'hours',
        'product_id',
        'active',
    ];

    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = max(0, (int) $value);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'product_order_id',
        'product_id',
        'quantity',
    ];

    public function batch_operations()
    {
        return $this->hasMany(BatchOperation::class)->orderBy('created_at', 'desc');
    }
}

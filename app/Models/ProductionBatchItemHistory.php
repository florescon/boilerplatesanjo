<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatchItemHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_item_id',
        'batch_id', 
        'product_id', 
        'receive',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'batch_id');
    }

    public function production_batch_item()
    {
        return $this->belongsTo(ProductionBatchItem::class);
    }
}

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
}

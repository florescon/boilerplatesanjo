<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionOrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'production_order_id', 'quantity', 'comment', 'product_id',
    ];
}

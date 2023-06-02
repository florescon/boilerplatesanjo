<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchProductReceive extends Model
{
    use HasFactory, SoftDeletes;

    protected $touches = ['batch_product'];

    protected $fillable = [
        'batch_product_id',
        'product_id',
        'quantity',
        'comment',
        'approved',
        'approved_by',
    ];

    /**
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function batch_product()
    {
        return $this->belongsTo(BatchProduct::class)->withTrashed();
    }

}

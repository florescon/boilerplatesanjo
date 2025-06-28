<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Domains\Auth\Models\User;

class CartProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'quantity',
        'price',
        'comment',
        'type',
        'user_id',
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
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function total()
    {
        return $this->price * $this->quantity;
    }

    public function getTotalAttribute()
    {
        return $this->total();
    }

    public function getLastRecordTable()
    {
        return DB::table('cart_products')->latest('id')->first();
    }
}

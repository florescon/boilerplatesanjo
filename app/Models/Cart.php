<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'quantity',
        'price',
        'comment',
        'type',
        'user_id',
        'branch_id',
        'price_without_tax',
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
        return DB::table('carts')->latest('id')->first();
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] =  str_replace(',', '', $value);
    }

    public function setPriceWithoutTaxAttribute($value)
    {
        $this->attributes['price_without_tax'] =  str_replace(',', '', $value);
    }

}

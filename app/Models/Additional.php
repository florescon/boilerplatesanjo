<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Additional extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'material_id', 
        'quantity',
        'price',
        'price_without_tax',
        'comment',
        'type',
        'user_id',
        'branch_id',
        'date_entered', 
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
    public function material()
    {
        return $this->belongsTo(Material::class)->withTrashed();
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
        return DB::table('additionals')->latest('id')->first();
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] =  str_replace(',', '', $value);
    }

    public function setPriceWithoutTaxAttribute($value)
    {
        $this->attributes['price_without_tax'] =  str_replace(',', '', $value);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_entered' => 'date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date_entered',
    ];
    
}

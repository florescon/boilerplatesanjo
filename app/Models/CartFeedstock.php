<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Domains\Auth\Models\User;

class CartFeedstock extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id', 
        'quantity',
        'price',
        'comment',
        'type',
        'user_id',
    ];

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
        return DB::table('cart_feedstocks')->latest('id')->first();
    }
}

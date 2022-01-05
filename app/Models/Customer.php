<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;

class Customer extends Model
{
    use HasFactory;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'address', 'rfc', 'type_price'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function isRetail(): bool
    {
        return $this->type_price === User::PRICE_RETAIL;
    }

    /**
     * @return mixed
     */
    public function isAverageWholesale(): bool
    {
        return $this->type_price === User::PRICE_AVERAGE_WHOLESALE;
    }
    /**
     * @return mixed
     */
    public function isWholesale(): bool
    {
        return $this->type_price === User::PRICE_WHOLESALE;
    }

    /**
     * @param $type_price
     *
     * @return bool
     */
    public function isTypePrice($type_price): bool
    {
        return $this->type_price === $type_price;
    }
}
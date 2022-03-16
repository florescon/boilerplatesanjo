<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Domains\Auth\Models\User;

class Departament extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'comment',
        'is_enabled',
        'phone',
        'address',
        'rfc',
        'type_price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function scopeDisabled(Builder $query): Builder
    {
        return $query->where('is_enabled', false);
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

    public function getTypePriceLabelAttribute()
    {
        if($this->isRetail()){
            return __('Retail price');
        }
        elseif($this->isAverageWholesale()){
            return __('Average wholesale price');
        }
        elseif($this->isWholesale()){
            return __('Wholesale price');
        }

        return __('Retail price');
    }

    /**
     * @return string
     */
    public function getIsEnabledDepartamentAttribute()
    {
        if ($this->is_enabled) {
            return "<span class='badge badge-success'>".__('Enabled').'</span>';
        }

        return "<span class='badge badge-danger'>".__('Disabled').'</span>';
    }

    /**
     * @return string
     */
    public function getIsDisabledAttribute()
    {
        if (!$this->is_enabled) {
            return "<span class='badge badge-danger'>".__('Disabled').'</span>';
        }

        return '';
    }

    public function getUpdatedAtFormattedAttribute() {
        return date('d/m h:i A', strtotime($this->attributes['updated_at']));
    }

    public function getCreatedAtFormattedAttribute() {
        return date('d/m h:i A', strtotime($this->attributes['created_at']));
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at->format('M, d Y');
    }

    public function getDateForHumansCreatedAttribute()
    {
        return $this->created_at->format('M, d Y');
    }
}

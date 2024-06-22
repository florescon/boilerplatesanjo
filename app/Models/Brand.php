<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'website',
        'description',
        'position',
        'is_internal',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    /**
     * Count the number products.
     *
     * @return int
     */
    public function getTotalVariantsAttribute() : int
    {
        return Product::where('parent_id', NULL)->whereType(true)->count();
    }

    /**
     * Count the number products.
     *
     * @return int
     */
    public function getcountProductsAttribute() : int
    {
        return $this->products->where('parent_id', NULL)->count();
    }

    public function getTotalPercentageAttribute() 
    {
        return ($this->count_products * 100) / $this->total_variants;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM, YY h:mm:ss a');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM, YY h:mm:ss a');
    }

    public function getIsInternalLabelAttribute()
    {
        if($this->is_internal){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-dark'>".__('No').'</span>';
    }


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ]
        ];
    }


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_internal' => 'boolean',
    ];

}

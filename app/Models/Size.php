<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

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

    public function subproducts(): HasMany
    {
        return $this->hasMany(Product::class, 'size_id')->with('parent')
            ->whereHas('parent', function ($query) {
                $query->whereNull('deleted_at');
            })
        ;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'size_id')->with('parent')
            ->whereHas('parent', function ($query) {
                $query->whereNull('deleted_at');
            })->groupBy('parent_id')
        ;
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
    public function getTotalVariantsSubproductsAttribute() : int
    {
        return Product::where('parent_id', '<>', NULL)->count();
    }

    /**
     * Count the number products.
     *
     * @return int
     */
    public function getcountProductsAttribute() : int
    {
        return $this->products->count();
    }

    /**
     * Count the number products.
     *
     * @return int
     */
    public function getcountProductAttribute() : int
    {
        return $this->products->count();
    }

    public function getTotalPercentageAttribute() 
    {
        return ($this->count_products * 100) / $this->total_variants;
    }

    public function getTotalPercentageSubproductsAttribute() 
    {
        return ($this->count_products * 100) / $this->total_variants_subproducts;
    }

    /**
     * Get the size's short_name.
     *
     * @param  string  $value
     * @return string
     */
    public function getShortNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function getUndefinedCodingAttribute(): ?string
    {
        if($this->short_name == null){
            return "<span class='badge badge-danger'>".__('undefined size coding').'</span>';
        }
        return '';
    }

    public function getUndefinedIconCodingAttribute(): ?string
    {
        if($this->short_name == null){
            return 
                '<button type="button" class="btn btn-white" data-toggle="tooltip" data-placement="top" title="'.__('undefined size coding').'">
                      <i class="fa fa-exclamation-triangle icon-red" aria-hidden="true"></i>
                </button>'
                ;
        }
        return '';
    }

    public function getIsExtraLabelAttribute()
    {
        if($this->is_extra){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-dark'>".__('No').'</span>';
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM, YY h:mm:ss a');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM, YY h:mm:ss a');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'short_name',
        'keywords',
        'sort',
        'is_extra',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_extra' => 'boolean',
    ];
}

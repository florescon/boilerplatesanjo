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
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id')->with('parent')
            ->whereHas('parent', function ($query) {
                $query->whereNull('deleted_at');
            })
        ;
    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id')->with('parent')
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
        return $this->product->count();
    }

    public function getTotalPercentageAttribute() 
    {
        return ($this->count_products * 100) / $this->total_variants;
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

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Color extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'short_name',
        'color',
        'secondary_color',
        'slug'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'color_id')->with('parent')
            ->whereHas('parent', function ($query) {
                $query->whereNull('deleted_at');
            })
        ;
    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'color_id')->with('parent')
            ->whereHas('parent', function ($query) {
                $query->whereNull('deleted_at');
            })->groupBy('parent_id');
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

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'LIKE', '%'.$query.'%')
                ->orWhere('color', 'LIKE', '%'.$query.'%');
    }

    public function getVisualColorAttribute()
    {
        return !$this->color ? "<span class='badge badge-light font-italic'>".__('undefined color').'</span>' : '';
    }

    public function getUndefinedCodingAttribute(): ?string
    {
        if($this->short_name == null){
            return "<span class='badge badge-danger'>".__('undefined color coding').'</span>';
        }
        return '';
    }

    public function getDateForHumansAttribute(): ?string
    {
        return $this->updated_at ? $this->updated_at->format('M, d Y') : null;
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

}

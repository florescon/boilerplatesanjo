<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the city associated with the Vendor.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'vendor_id');
    }

    public function getShortNameLabelAttribute()
    {
        if($this->short_name){
            return $this->short_name;
        }

        return " <span class='badge badge-secondary'>".__('undefined').'</span>';
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

    /**
     * Count the number materials.
     *
     * @return int
     */
    public function getcountMaterialsAttribute() : int
    {
        return $this->materials->count();
    }

    /**
     * Count the number materials.
     *
     * @return int
     */
    public function getTotalVariantsMateriaAttribute() : int
    {
        return Material::count();
    }

    public function getTotalPercentageMateriaAttribute() 
    {
        return number_format(($this->count_materials * 100) / $this->total_variants_materia, 2);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'city_id',
        'address',
        'rfc',
        'comment',
        'short_name',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'count_materials',
        'total_variants_materia',
    ];
}

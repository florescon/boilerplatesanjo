<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
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

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'family_id');
    }

    public function getNameLabelAttribute()
    {
        if($this->name){
            return "<span class='badge badge-dark'>".$this->name.'</span>';
        }

        return '';
    }

    /**
     * Get the family's name.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucfirst(strtolower($value));
    }

    /**
     * Count the number products.
     *
     * @return int
     */
    public function getcountMaterialsAttribute() : int
    {
        return $this->materials->count();
    }

    /**
     * Set the family's name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM h:mm:ss a');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM h:mm:ss a');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

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
        'slug'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'color_id')->withTrashed();
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'LIKE', '%'.$query.'%')
                ->orWhere('color', 'LIKE', '%'.$query.'%');
    }


    public function getDateForHumansAttribute()
    {
        return $this->updated_at->format('M, d Y');
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

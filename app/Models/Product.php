<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Sluggable;

    protected $cascadeDeletes = ['children'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'code',
        'price',
        'file_name',
        'description',
        'color_id',
        'size_id',
        'parent_id',
        'sort',
        'status',
    ];


    public function getDescriptionLimitedAttribute()
    {
        return Str::words($this->description, '15');
    }
    
    public function size()
    {
        return $this->belongsTo(Size::class)->withTrashed();
    }

    public function color()
    {
        return $this->belongsTo(Color::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(Product::class)->with('parent');
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(Product::class, 'parent_id')->with('children', 'size', 'color')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function consumption()
    {
        // return $this->hasMany(Consumption::class);
        return $this->hasMany(Consumption::class)->with('material');
    }

    public function getTotalStock()
    {
        return $this->children->sum(function($parent) {
          return $parent->stock + $parent->stock_revision + $parent->stock_store;
        });
    }

    public function log()
    {
        return $this->morphMany(Log::class, 'logable');
    }

    public static function boot()
    {
        parent::boot();

        // cause a restore of a folder to cascade
        // to children so they are also restored

        static::created(function($create_product) {

            $create_product->log()->create(['body' => 
                (!$create_product->name ? 'Sub' : null).'Producto creado '.
                ($create_product->name ?? null).' '.
                ($create_product->parent->name ?? null).' - '.
                ($create_product->color->name ?? null).', '.
                ($create_product->size->name ?? null)]);
        }); 


        static::restoring(function($restore_subproducts) {
            // $restore_subproducts->children->withTrashed()->get()
            //     ->each(function($subprod) {
            //         $subprod->restore();
            //     });

            $restore_subproducts->children()->withTrashed()->get()
                ->each(function($subprod) {
                    $subprod->restore();
                });
        });
    }

}

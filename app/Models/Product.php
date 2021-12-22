<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Traits\Scope\ProductScope;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Sluggable, ProductScope;

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
                'source' => 'name',
                'onUpdate' => false,
            ]
        ];
    }

    // public $with = ['advanced'];

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
        'average_wholesale_price',
        'wholesale_price',
        'file_name',
        'description',
        'color_id',
        'size_id',
        'line_id',
        'brand_id',
        'parent_id',
        'sort',
        'automatic_code',
        'status',
        'type',
        'model_product_id',
    ];

    public function getDescriptionLimitedAttribute()
    {
        return Str::words($this->description, '15');
    }

    /**
     * Get the line associated with the Product.
     */
    public function line()
    {
        return $this->belongsTo(Line::class)->withTrashed();
    }

    /**
     * Get the brand associated with the Product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class)->withTrashed();
    }
    
    /**
     * Get the model product associated with the Product.
     */
    public function model_product()
    {
        return $this->belongsTo(ModelProduct::class)->withTrashed();
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
     * @return string
     */
    public function getSizeNameAttribute()
    {
        return $this->size_id ? '&nbsp;&nbsp; '.$this->size->name : '';
    }

    /**
     * @return string
     */
    public function getColorNameAttribute()
    {
        return $this->color_id ? '| '.$this->color->name : '';
    }

    /**
     * @return string
     */
    public function getFirstCharacterNameAttribute()
    {
        return $this->name ? substr($this->name, 0, 1) : '';
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->parent_id != null ? '<strong>'.$this->parent->name.'</strong> <em>'.$this->size_name.' '.$this->color_name.'</em>' : $this->name." <span class='badge badge-primary'>".__('Main').'</span>';
    }

    /**
     * @return string
     */
    public function getOnlyAttributesAttribute()
    {
        return $this->size_name.' '.$this->color_name;
    }

    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->with('children', 'size', 'color');
    }

    /**
     * @return mixed
     */
    public function childrenWithTrashed()
    {
        return $this->hasMany(self::class, 'parent_id')->with('children', 'size', 'color')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function childrenOnlyColors()
    {
        return $this->hasMany(self::class, 'parent_id')->with('children', 'color');
    }

    /**
     * @return mixed
     */
    public function childrenOnlySizes()
    {
        return $this->hasMany(self::class, 'parent_id')->with('children', 'size')->withTrashed();
    }

    /**
     * @return bool
     */
    public function hasCodeSubproduct()
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isChildren()
    {
        return $this->parent_id;
    }

    /**
     * @return bool
     */
    public function isService(): bool
    {
        return $this->type;
    }

    public function getCodeSubproductAttribute()
    {
        if(!$this->hasCodeSubproduct()){
            return $this->parent->code." <span class='badge badge-secondary'>".__('General').'</span>';
        }

        return $this->code;
    }

    public function getCodeLabelAttribute()
    {
        if(!$this->hasCodeSubproduct()){
            return $this->parent->code;
        }

        return $this->code;
    }

    /**
     * @return bool
     */
    public function hasPriceSubproduct()
    {
        return $this->price;
    }

    public function getPriceSubproductAttribute()
    {
        if(!$this->hasPriceSubproduct()){
            return $this->parent->price." <span class='badge badge-secondary'>".__('General').'</span>';
        }

        return $this->price;
    }

    public function getPriceSubproductLabelAttribute()
    {
        if(!$this->hasPriceSubproduct()){
            return $this->parent->price;
        }

        return $this->price;
    }

    /**
     * @return string
     */
    public function getStatusNameAttribute()
    {
        if ($this->status == TRUE) {
            return '<i class="bg-success"></i>'. __('Active');
        }

        return '<i class="bg-warning"></i>'. __('Inactive');
    }

    /**
     * Get the description associated with the product.
     */
    public function advanced()
    {
        return $this->hasOne(Description::class);
    }

    public function isActiveAdvanced()
    {
        return $this->advanced->status ?? false;
    }

    /**
     * @return bool
     */
    public function getDifferentNullAdvancedAttribute(): bool
    {
        return $this->advanced->information != null;
    }

    /**
     * @return string
     */
    public function getStatusAdvancedAttribute()
    {
        if ($this->isActiveAdvanced()) {
            return "<span class='badge badge-success'>".__('Active').'</span>';
        }

        return "<span class='badge badge-danger'>".__('Inactive').'</span>';
    }

    /**
     * @return mixed
     */
    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function getTotalPicturesAttribute(): int
    {
        return $this->pictures->count();
    }

    /**
     * @return mixed
     */
    public function consumption()
    {
        // return $this->hasMany(Consumption::class);
        return $this->hasMany(Consumption::class)->with('material');
    }

    public function consumption_filter()
    {
        return $this->hasManyThrough(Consumption::class, self::class, 'id', 'product_id', 'parent_id', 'id')->with('material');
    }

    public function getTotalConsumptionBySize($byID)
    {
        return $this->consumption->where('size_id', $byID)->count();
    }
    public function getTotalConsumptionByColor($byID)
    {
        return $this->consumption->where('color_id', $byID)->count();
    }

    public function getTotalPicturesByColor($byID)
    {
        return $this->pictures->where('color_id', $byID)->count();
    }

    public function getTotalStockAttribute()
    {
        return $this->children->sum(function($parent) {
          return $parent->stock + $parent->stock_revision + $parent->stock_store;
        });
    }

    public function getTotalStockbyID($byID)
    {
        return $this->children->where('id', $byID)->sum(function($parent) {
          return $parent->stock + $parent->stock_revision + $parent->stock_store;
        });
    }

    public function getTotStock()
    {
        return $this->children->sum(function($parent) {
          return $parent->stock;
        });
    }
    public function getTotStockRev()
    {
        return $this->children->sum(function($parent) {
          return $parent->stock_revision;
        });
    }
    public function getTotStockStore()
    {
        return $this->children->sum(function($parent) {
          return $parent->stock_store;
        });
    }

    public function log()
    {
        return $this->morphMany(Log::class, 'logable');
    }

    public function getDateForHumansSpecialAttribute()
    {
        return $this->parent_id != null ? $this->parent->created_at : $this->created_at;
    }

    public function getNewProductAttribute()
    {
        if($this->created_at){
            if($this->created_at->gt(Carbon::now()->subMonth())){
                return __('New'). ' |';
            }
        }

        return '';
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('M, d Y') : '';
    }

    public static function boot()
    {
        parent::boot();

        // cause a restore of a folder to cascade
        // to children so they are also restored

        // static::created(function($create_product) {

        //     $create_product->log()->create(['body' => 
        //         (!$create_product->name ? 'Sub' : null).'Producto creado '.
        //         ($create_product->name ?? null).' '.
        //         ($create_product->parent->name ?? null).' - '.
        //         ($create_product->color->name ?? null).', '.
        //         ($create_product->size->name ?? null)]);
        // }); 


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

    /**
     * Get the product's code.
     *
     * @param  string  $value
     * @return string
     */
    public function getCodeAttribute($value)
    {
        return strtoupper($value);
    }

     /**
     * Set the product's name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'boolean',
        'status' => 'boolean',
        'automatic_code' => 'boolean',
    ];
}
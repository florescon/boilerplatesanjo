<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Size;
use App\Models\Color;
use App\Models\Line;
use App\Models\Picture;
use App\Models\Description;
use App\Models\Favorite;
use Carbon\Carbon;
use App\Models\Frontend\Traits\Scope\ProductScope;

class Product extends Model
{
    use HasFactory, SoftDeletes, ProductScope;


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
        'file_name',
        'description',
        'line_id',
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

    /**
     * Get the line associated with the Product.
     */
    public function line()
    {
        return $this->belongsTo(Line::class)->withTrashed();
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
        return $this->size_id ? '| '.$this->size->name : '';
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
    public function getFullNameAttribute()
    {
        return '<strong>'.$this->parent->name.'</strong> '.$this->size_name.' '.$this->color_name;
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
        return $this->hasMany(self::class, 'parent_id')->with('children', 'size', 'color')->withTrashed();
    }

    /**
     * @return bool
     */
    public function hasCodeSubproduct()
    {
        return $this->code;
    }

    public function getCodeSubproductAttribute()
    {
        if(!$this->hasCodeSubproduct()){
            return $this->parent->code." <span class='badge badge-secondary'>".__('General').'</span>';
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

    /**
     * Get the description associated with the product.
     */
    public function advanced()
    {
        return $this->hasOne(Description::class);
    }

    /**
     * Get the description associated with the product.
     */
    public function favorite()
    {
        return $this->hasOne(Favorite::class);
    }

    public function favoriteByAuth(int $user)
    {
        return $this->favorite->where('audi_id', $user)->first();
    }


    /**
     * Get the description associated with the product.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * @return mixed
     */
    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function getTotalPicturesAttribute()
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
        return $this->hasManyThrough(Consumption::class, Product::class, 'id', 'product_id', 'parent_id', 'id')->with('material');
    }

    public function getTotalConsumptionBySize($byID)
    {
        return $this->consumption->where('size_id', $byID)->count();
    }
    public function getTotalConsumptionByColor($byID)
    {
        return $this->consumption->where('color_id', $byID)->count();
    }


    public function getTotalStock()
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

    public function getDateForHumansAttribute()
    {
        return $this->updated_at->format('M, d Y');
    }


    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';    
}

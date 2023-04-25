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
use App\Domains\Auth\Models\User;

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
        'vendor_id',
        'cost',
        'special_price',
        'stock',
        'stock_revision',
        'stock_store',
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
     * Get the vendor associated with the Product.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class)->withTrashed();
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
    public function getSizeNameClearAttribute()
    {
        return $this->size_id ? $this->size->name.', ' : '';
    }

    /**
     * @return string
     */
    public function getSizeSortAttribute()
    {
        return $this->size_id ? $this->size->sort : 0;
    }

    /**
     * @return string
     */
    public function getColorSortAttribute()
    {
        return $this->color_id ? $this->color->sort : 0;
    }

    /**
     * @return string
     */
    public function getColorNameClearAttribute()
    {
        return $this->color_id ? $this->color->name : '';
    }

    /**
     * @return string
     */
    public function getFirstCharacterNameAttribute()
    {
        return $this->name ? substr($this->name, 0, 3) : '';
    }

    /**
     * @return string
     */
    public function getOnlyNameAttribute()
    {
        if($this->parent_id !== null){
            return $this->parent->name;
        }
        else{
            if(!$this->isProduct()){
                return $this->name;
            }
            else{
                return $this->name;
            }
        }
    }

    /**
     * @return string
     */
    public function getOnlyParametersAttribute()
    {
        if($this->parent_id !== null){
            return '<em>'.$this->size_name.' '.$this->color_name.'</em>';
        }
        else{
            if(!$this->isProduct()){
                return '';
            }
            else{
                return '';
            }
        }
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        if($this->parent_id !== null){
            return '<strong>'.$this->parent->name.'</strong> <em>'.$this->size_name.' '.$this->color_name.'</em>';
        }
        else{
            if(!$this->isProduct()){
                return $this->name." <span class='badge badge-info' style='color: white; background-color: #85144b;'>&nbsp;".__('Service') .'&nbsp;</span>';
            }
            else{
                return $this->name." <span class='badge badge-primary'>".__('Main').'</span>';
            }
        }
    }

    /**
     * @return string
     */
    public function getFullNameLinkAttribute()
    {
        if($this->parent_id !== null){
            return '<a tabindex="-1" target="_blank" href="'.route('admin.product.edit', $this->parent_id).'"><strong>'.$this->parent->name.'</strong></a> <em>'.$this->size_name.' '.$this->color_name.'</em>';
        }
        else{
            if(!$this->isProduct()){
                return $this->name." <span class='badge badge-info' style='color: white; background-color: #85144b;'>".__('Service').'</span>';
            }
            else{
                return $this->name." <span class='badge badge-primary'>".__('Main').'</span>';
            }
        }
    }

    /**
     * @return string
     */
    public function getFullNameLinkMainAttribute()
    {
        if($this->parent_id !== null){
            return '<a tabindex="-1" href="'.route('admin.product.edit', $this->parent_id).'"><strong>'.$this->parent->name.'</strong></a>';
        }
        else{
            if(!$this->isProduct()){
                return $this->name." <span class='badge badge-info' style='color: white; background-color: #85144b;'>".__('Service').'</span>';
            }
            else{
                return '<a href="'.route('admin.product.edit', $this->id).'"><strong>'.$this->name.'</strong></a>'." <span class='badge badge-primary'>".__('Main').'</span>';
            }
        }
    }

    /**
     * @return string
     */
    public function getFullNameClearAttribute()
    {
        if($this->parent_id !== null){
            return $this->parent->name.', '.$this->size_name_clear.' '.$this->color_name_clear;
        }
        else{
            if(!$this->isProduct()){
                return $this->name;
            }
            else{
                return $this->name;
            }
        }
    }

    /**
     * @return string
     */
    public function getFullNameClearSortAttribute()
    {
        if($this->parent_id !== null){
            return $this->parent->name.' '.$this->color_name_clear.' '.$this->size_sort;
        }
        else{
            if(!$this->isProduct()){
                return $this->name;
            }
            else{
                return $this->name;
            }
        }
    }

    /**
     * @return string
     */
    public function getFullNameClearLineAttribute()
    {
        if($this->parent_id !== null){
            return $this->parent->name.' '.$this->color_name_clear;
        }
        else{
            if(!$this->isProduct()){
                return $this->name;
            }
            else{
                return $this->name;
            }
        }
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
        return $this->belongsTo(self::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->with('parent', 'size', 'color');
    }

    /**
     * @return mixed
     */
    public function history()
    {
        return $this->hasMany(ProductHistory::class);
    }

    /**
     * @return mixed
     */
    public function history_subproduct()
    {
        return $this->hasMany(ProductHistory::class, 'subproduct_id');
    }

    public function getTotalHistory(?string $dateInput = null, ?string $dateOutput = null, bool $isOutput = false): int
    {
        if($this->isChildren()){
            if($dateInput){
                return empty($dateOutput) ? 
                    $this->history_subproduct()->where('is_output', $isOutput)->whereBetween('updated_at', [$dateInput.' 00:00:00', now()])->sum('stock') 
                        : 
                    $this->history_subproduct()->where('is_output', $isOutput)->whereBetween('updated_at', [$dateInput.' 00:00:00', $dateOutput.' 00:00:00'])->sum('stock');
            }

            return $this->history_subproduct()->where('is_output', $isOutput)->whereMonth('created_at', now()->month)->sum('stock');
        }
        else{
            if($dateInput){
                return empty($dateOutput) ? 
                $this->history()->where('is_output', $isOutput)->whereBetween('updated_at', [$dateInput.' 00:00:00', now()])->sum('stock')
                    :
                $this->history()->where('is_output', $isOutput)->whereBetween('updated_at', [$dateInput.' 00:00:00', $dateOutput.' 00:00:00'])->sum('stock');
            }
    
            return $this->history()->where('is_output', $isOutput)->whereMonth('created_at', now()->month)->sum('stock');
        }

        return false;
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
    public function isProduct(): bool
    {
        return $this->type;
    }

    public function getCodeSubproductAttribute()
    {
        if(!$this->hasCodeSubproduct() && $this->isProduct()){
            return $this->parent->code." <span class='badge badge-secondary'>".__('General').'</span>';
        }

        return $this->code ?: __('undefined');
    }

    public function getCodeSubproductClearAttribute()
    {
        if(!$this->hasCodeSubproduct() && $this->isProduct()){
            return $this->parent->code;
        }

        return $this->code ?: '--';
    }

    public function getParentCodeAttribute(){
        if($this->isProduct()){
            return $this->parent->code;
        }

        return $this->code ?? '';
    }

    public function getCodeLabelAttribute()
    {
        if(!$this->hasCodeSubproduct() && $this->isProduct()){
            return $this->parent->code;
        }

        return $this->code ?? __('undefined');
    }

    public function getIdLabelAttribute()
    {
        return "<span class='badge badge-primary'>".$this->id.'</span>';
    }

    /**
     * @return bool
     */
    public function hasPriceSubproduct()
    {
        return $this->price;
    }

    /**
     * @return bool
     */
    public function hasAverageWholesalePriceSubproduct()
    {
        return $this->average_wholesale_price;
    }

    /**
     * @return bool
     */
    public function hasWholesalePriceSubproduct()
    {
        return $this->wholesale_price;
    }

    /**
     * @return bool
     */
    public function hasSpecialPriceSubproduct()
    {
        return $this->special_price;
    }

    public function getPriceSubproductAttribute()
    {
        if(!$this->hasPriceSubproduct()){
            return $this->parent->price." <span class='badge badge-secondary'>".__('General').'</span>';
        }

        return $this->price != 0 ? $this->price : $this->parent->price;
    }

    public function getPriceAverageWholesaleSubproductAttribute()
    {
        if(!$this->hasAverageWholesalePriceSubproduct()){
            return $this->parent->average_wholesale_price." <span class='badge badge-secondary'>".__('General').'</span>';
        }

        return $this->average_wholesale_price != 0 ? $this->average_wholesale_price : $this->parent->average_wholesale_price;
    }

    public function getPriceWholesaleSubproductAttribute()
    {
        if(!$this->hasWholesalePriceSubproduct()){
            return $this->parent->wholesale_price." <span class='badge badge-secondary'>".__('General').'</span>';
        }

        return $this->wholesale_price != 0 ? $this->wholesale_price : $this->parent->wholesale_price;
    }

    public function getPriceSpecialSubproductAttribute()
    {
        if(!$this->hasSpecialPriceSubproduct()){
            return $this->parent->special_price." <span class='badge badge-secondary'>".__('General').'</span>';
        }

        return $this->special_price != 0 ? $this->special_price : $this->parent->special_price;
    }

    public function getPriceSubproductLabelAttribute()
    {
        if(!$this->hasPriceSubproduct()){
            return $this->parent->price;
        }

        return $this->price;
    }

    public function getNameStock(?string $nameStock = null)
    {
        if($this->isProduct()){
            switch ($type_price) {
                case 'stock':
                    return 1;
                case 'revision':
                    return 2;
                case 'store':
                    return 3;
            }
        }
    }

    public function getPrice(?string $type_price = null)
    {
        if($this->isProduct()){
            switch ($type_price) {
                case User::PRICE_RETAIL:
                    if(!$this->hasPriceSubproduct())
                        return $this->parent->price;
                    else
                        return $this->price !== 0 ? $this->price : $this->parent->price;
                case User::PRICE_AVERAGE_WHOLESALE:
                    if(!$this->hasAverageWholesalePriceSubproduct())
                        return $this->parent->average_wholesale_price ? $this->parent->average_wholesale_price : $this->parent->price ;
                    else
                        return $this->average_wholesale_price !== 0 ? $this->average_wholesale_price : $this->parent->average_wholesale_price;
                case User::PRICE_WHOLESALE:
                    if(!$this->hasWholesalePriceSubproduct())
                        return $this->parent->wholesale_price ? $this->parent->wholesale_price : $this->parent->price;
                    else
                        return $this->wholesale_price !== 0 ? $this->wholesale_price : $this->parent->wholesale_price;
                case User::PRICE_SPECIAL:
                    if(!$this->hasSpecialPriceSubproduct())
                        return $this->parent->special_price ? $this->parent->special_price : $this->parent->price;
                    else
                        return $this->special_price !== 0 ? $this->special_price : $this->parent->special_price;
            }

            return $this->price !== 0 ? $this->price : $this->parent->price;
        }

        return $this->price;
    }

    public function getPriceWithIvaApply($price)
    {
        return number_format($price + ((setting('iva') / 100) * $price), 2);
    }

    public function getPriceWithIva(?string $type_price = null)
    {
        $getPrice = $this->getPrice($type_price ?? User::PRICE_RETAIL);
        return number_format($getPrice + ((setting('iva') / 100) * $getPrice), 2);
    }

    public function getPriceWithoutIva(?string $type_price = null)
    {
        $getPrice = $this->getPrice($type_price ?? User::PRICE_RETAIL);
        return number_format($getPrice, 2);
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
        return $this->advanced->information;
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
        return $this->consumption()->where('size_id', $byID)->count();
    }
    public function getTotalConsumptionByColor($byID)
    {
        return $this->consumption()->where('color_id', $byID)->count();
    }

    public function getTotalPicturesByColor($byID)
    {
        return $this->pictures->where('color_id', $byID)->count();
    }

    public function getTotalStockAttribute()
    {
        if($this->isChildren()){
            return $this->stock + $this->stock_store + $this->stock_revision;  
        }

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
        if($this->isChildren()){
            return $this->stock;  
        }

        return $this->children->sum(function($parent) {
          return $parent->stock;
        });
    }

    public function getTotalByTypeStock(?int $colorId = null, ?string $type_stock = null)
    {
        return $this->children->where('color_id', $colorId)->sum(function($parent) use($type_stock) {
          return $parent->$type_stock;
        });
    }

    public function getTotStockRev()
    {
        if($this->isChildren()){
            return $this->stock_revision;  
        }

        return $this->children->sum(function($parent) {
          return $parent->stock_revision;
        });
    }

    public function getTotStockStore()
    {
        if($this->isChildren()){
            return $this->stock_store;  
        }

        return $this->children->sum(function($parent) {
          return $parent->stock_store;
        });
    }

    public function getStoreStockAttribute()
    {
        if($this->isChildren()){
            return $this->stock_store;  
        }

        return $this->children->sum(function($parent) {
          return $parent->stock_store;
        });
    }

    public function log()
    {
        return $this->morphMany(Log::class, 'logable');
    }

    public function session()
    {
        return $this->hasOne(Session::class);
    }

    public function getDateForHumansSpecialAttribute()
    {
        return $this->parent_id !== null ? $this->parent->created_at : $this->created_at;
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
        return $this->updated_at ? $this->updated_at->isoFormat('D, MMM, YY') : '';
    }

    /**
     * @return string
     */
    public function color_stock(int $stock): string
    {
        if($stock > 0){
            return 'text-primary';
        } 
        elseif($stock < 0){
            return 'text-danger';
        }
        else{
            return 'text-dark';
        }

        return 'text-dark';
    }

    public static function boot()
    {
        parent::boot();

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

    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    protected $appends = ['full_name_clear_sort'];

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
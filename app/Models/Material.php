<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'part_number',
        'name',
        'description',
        'acquisition_cost',
        'price',
        'stock',
        'unit_id',
        'color_id',
        'size_id',
        'vendor_id',
        'short_name',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'stock_formatted',
        'full_name_clear',
    ];

    /**
     * Return formatted stock.
     */
    public function getStockFormattedAttribute(): string
    {
        return rtrim(rtrim(sprintf('%.8F', $this->stock), '0'), ".");
    }

    /**
     * Get the color associated with the Material.
     */
    public function color()
    {
        return $this->belongsTo(Color::class)->withTrashed();
    }

    /**
     * Get the size associated with the Material.
     */
    public function size()
    {
        return $this->belongsTo(Size::class)->withTrashed();
    }

    /**
     * Get the unit associated with the Material.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

    /**
     * Get the vendor associated with the Material.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function history()
    {
        return $this->hasMany(MaterialHistory::class);
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
    public function getUnitNameAttribute()
    {
        return $this->unit_id ? '<sup>'.$this->unit->name.'</sup>' : '';
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return '<strong>'.$this->name.'</strong> '.$this->unit_name.' '.$this->size_name.' '.$this->color_name;
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
        return $this->size_id ? ', '.$this->size->name : '';
    }

    /**
     * @return string
     */
    public function getColorNameClearAttribute()
    {
        return $this->color_id ? ', '.$this->color->name : '';
    }

    /**
     * @return string
     */
    public function getUnitNameClearAttribute()
    {
        return $this->unit_id ? ', '.$this->unit->name : '';
    }

    public function getNewMaterialAttribute()
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
        return $this->updated_at ? $this->updated_at->isoFormat('D, MMM') : '';
    }

    /**
     * @return string
     */
    public function getFullNameClearAttribute()
    {
        return $this->name.$this->size_name_clear.$this->color_name_clear;
    }

    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM h:mm:ss a');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM h:mm:ss a');
    }
}

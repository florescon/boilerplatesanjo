<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Consumption extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'material_id',
        'quantity',
        'color_id',
        'size_id',
        'puntual',
        'secondary_puntual',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'quantity_formatted',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'puntual' => 'boolean',
        'secondary_puntual' => 'boolean',
    ];

    /**
     * Return formatted stock.
     */
    public function getQuantityFormattedAttribute(): string
    {
        return rtrim(rtrim(sprintf('%.8F', $this->quantity), '0'), ".");
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id')->with('color', 'size', 'unit', 'vendor', 'family')->withTrashed();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationPreconsumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id', 'material_id', 'quantity', 'received', 'original', 'processed'
    ];

    /**
     * @return mixed
     */
    public function station()
    {
        return $this->belongsTo(Station::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function material()
    {
        return $this->belongsTo(Material::class)->with('unit', 'size', 'color')->withTrashed();
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'processed' => 'boolean',
    ];
}

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
    ];


    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id')->with('color', 'size', 'unit')->withTrashed();
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'out_id',
        'material_id',
        'quantity',
        'price',
        'comment',
        'type',
    ];

    /**
     * @return mixed
     */
    public function out()
    {
        return $this->belongsTo(Out::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function material()
    {
        return $this->belongsTo(Material::class)->withTrashed();
    }

    public function getTotalByProductAttribute()
    {
        return $this->quantity * $this->price;
    }
}

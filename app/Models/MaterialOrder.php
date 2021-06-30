<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialOrder extends Model
{
    use HasFactory;

    protected $table = 'material_orders';

    protected $fillable = [
        'order_id', 'material_id', 'quantity' 
    ];

    /**
     * @return mixed
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

}

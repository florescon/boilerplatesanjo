<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'material_histories';

    protected $fillable = [
        'material_id', 'old_stock', 'stock', 'old_price', 'price', 'audi_id' 
    ];

    /**
     * @return mixed
     */
    public function material()
    {
        return $this->belongsTo(Material::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }
}

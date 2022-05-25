<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class MaterialHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'material_histories';

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

    public function getClassStockAttribute($value)
    {
        return $this->stock > 0 ? 'bg-primary' : 'bg-danger'; 
    }

    public function getCreatedAtIsoAttribute($value)
    {
        return $this->created_at ? Carbon::parse($this->created_at)->isoFormat('D, MMM h:mm:ss a') : '';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'material_id', 'old_stock', 'stock', 'old_price', 'price', 'audi_id', 'comment',
    ];
}

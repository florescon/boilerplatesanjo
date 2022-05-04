<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Auth\Models\User;

class ProductInventory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'capture',
        'stock',
        'audi_id',
        'type',
        'comment',
        'inventory_id'
    ];

    /**
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class)->withTrashed();
    }

    public function difference()
    {
        return abs($this->stock - $this->capture);
    }

    public function getDifferenceAttribute()
    {
        $this->difference();
    }

    public function getDescriptionDifferenceAttribute()
    {
        if($this->difference() == 0) {
            return "<span class='badge badge-success'><i class='cil-check'></i></span>";
        }
        elseif($this->stock > $this->capture) {
            return "<span class='badge badge-danger'>".__('Faltó de capturar').'</span>';
        }
        elseif($this->stock < $this->capture) {
            return "<span class='badge badge-primary'>".__('Sobrante').'</span>';
        }

        return '';
    }

    public function getDescriptionDifferenceFormattedAttribute()
    {
        if($this->difference() == 0) {
            return '';
        }
        elseif($this->stock > $this->capture) {
            return 'Faltó de capturar';
        }
        elseif($this->stock < $this->capture) {
            return 'Sobrante';
        }

        return '';
    }

}

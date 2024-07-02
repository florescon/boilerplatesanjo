<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Thread extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'vendor_id',
        'brand_id',
    ];

    /**
     * Get the vendor associated with the Thread.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class)->withTrashed();
    }

    /**
     * Get the brand associated with the Thread.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class)->withTrashed();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Scope\DateScope;
use Carbon\Carbon;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory, SoftDeletes, DateScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'comment',
        'audi_id',
        'type',
    ];

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductInventory::class);
    }

    public function total(): int
    {
        return $this->items->sum('stock');
    }

    public function captured(): int
    {
        return $this->items->sum('capture');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM, YY h:mm:ss a');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM, YY h:mm:ss a');
    }
}
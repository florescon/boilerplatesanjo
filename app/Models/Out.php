<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use App\Models\Traits\Scope\DateScope;

class Out extends Model
{
    use HasFactory, DateScope;

    protected $fillable = [
        'user_id',
        'folio',
        'customer_id',
        'description',
        'type',
    ];

    /**
     * @return mixed
     */
    public function feedstocks()
    {
        return $this->hasMany(OutProduct::class);
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withTrashed();
    }

    protected static function booted()
    {
        static::created(function ($out) {
            $out->update(['folio' => $out->last_out_by_type_skip]);
        });
    }

    public function getLastOutByTypeSkipAttribute()
    {   
        $getType = $this->type;

        $lastOrder = self::where('type', $getType)
            ->orderBy('folio', 'desc')
            ->first();

        // Return the next consecutive number
        return $lastOrder ? $lastOrder->folio + 1 : 1;
    }

    public function getTotalOutAttribute(): int
    {
        return $this->feedstocks->sum('price');
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at->isoFormat('D, MMM, YY');
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    public function getDateDiffForHumansCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

}

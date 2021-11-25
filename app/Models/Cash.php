<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Scope\DateScope;
use Carbon\Carbon;

class Cash extends Model
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
        'initial',
        'total',
        'audi_id',
        'checked',
    ];

    /**
     * @return mixed
     */
    public function finances()
    {
        return $this->hasMany(Finance::class)->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at->format('M, d Y');
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    public function getDateDiffForHumansCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getDateDiffForHumansCheckedAttribute()
    {
        return $this->checked->diffForHumans();
    }

    /**
     * @return bool
     */
    public function lastDay(): bool
    {
        if($this->checked->gt(Carbon::now()->subDay())){
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function getlastDayAttribute()
    {
        return $this->lastDay();
    }

    /**
     * @return string
     */
    public function getLastDayLabelAttribute()
    {
        return $this->lastDay() ? '('.__('Available 24 hours').')' : '';
    }

    protected $dates = ['checked'];

}

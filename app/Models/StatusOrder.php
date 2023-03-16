<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;

class StatusOrder extends Model
{
    use HasFactory;

    protected $table = 'status_orders';

    protected $fillable = [
        'order_id', 'status_id', 'audi_id',
    ];

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getNameStatusAttribute()
    {
        return $this->status->name;
    }

    public function getPercentageStatusAttribute()
    {
        return $this->status->percentage;
    }

    public function getColorStatusAttribute()
    {
        return $this->status->color;
    }

    public function getDateEnteredOrCreatedAttribute()
    {
        return !$this->date_entered ? $this->created_at->isoFormat('D, MMM') : $this->date_entered->isoFormat('D, MMM');
    }
}
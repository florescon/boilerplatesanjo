<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusOrder extends Model
{
    use HasFactory;

    protected $table = 'status_orders';

    protected $fillable = [
        'order_id', 'status_id', 
    ];

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }


    public function getNameStatusAttribute()
    {
        return $this->status->name;
    }

}

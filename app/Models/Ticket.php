<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 
        'status_id',
        'user_id',
        'date_entered',
        'audi_id',
    ];

    /**
     * @return mixed
     */
    public function assignments_direct()
    {
        return $this->hasMany(Assignment::class);
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
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id')->withTrashed();
    }

    public function getTotalProductsAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity;
        });
    }

    public function getTotalProductsAssignmentsAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity - $parent->assignments()->where('output', 0)->sum('quantity');
        });
    }

    public function getTotalProductsAssignmentTicketAttribute()
    {
        return $this->assignments_direct->sum('quantity');
    }

    public function getTotalOrderAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->updated_at->diffForHumans();
    }
}
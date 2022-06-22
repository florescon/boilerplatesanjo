<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use App\Models\Traits\Scope\DateScope;
use Carbon\Carbon;

class Assignment extends Model
{
    use HasFactory, DateScope;

    protected $fillable = [
        'order_id', 'ticket_id', 'status_id', 'user_id', 'quantity', 'assignmentable_id', 'assignmentable_type', 'output', 'received',
    ];

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
    public function ticket()
    {
        return $this->belongsTo(Ticket::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

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
    public function history()
    {
        return $this->hasMany(AssignmentHistory::class);
    }

    /**
     * @return bool
     */
    public function isOutput()
    {
        return $this->output;
    }

    public function getAvailableAttribute()
    {
        return $this->quantity - $this->received;
    }

    /**
     * @return string
     */
    public function getOutputtedLabelAttribute()
    {
        if ($this->isOutput()) {
            return "<span class='badge badge-success'><i class='cil-check'></i></span>";
        }

        return "<span class='badge badge-danger'>".__('To give out').'</span>';
    }

    public function getTotalQuantityAttribute()
    {
        if($this->assignmentable->product->parent->price_making){
            return $this->quantity * $this->assignmentable->product->parent->price_making;
        }

        return $this->quantity;
    }

    /**
     * Get the parent assignmentable model (product_order or consumption_order).
     */
    public function assignmentable()
    {
        return $this->morphTo();
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    public function getDateDiffForHumansCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM h:mm:ss a');
    }
}

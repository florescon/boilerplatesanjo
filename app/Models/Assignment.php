<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'ticket_id', 'status_id', 'user_id', 'quantity', 'assignmentable_id', 'assignmentable_type', 'output',
    ];

    /**
     * @return bool
     */
    public function isOutput()
    {
        return $this->output;
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

    /**
     * Get the parent assignmentable model (product_order or consumption_order).
     */
    public function assignmentable()
    {
        return $this->morphTo();
    }

}

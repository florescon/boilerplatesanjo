<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashable extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cashable_type',
        'cashable_id',
        'cash_id',
    ];

    /**
     * Get the parent commentable model (post or video).
     */
    public function cashable()
    {
        return $this->morphTo();
    }
}

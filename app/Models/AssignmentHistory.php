<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentHistory extends Model
{
    use HasFactory;

    protected $table = 'assignment_histories';

    /**
     * @return mixed
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assignment_id', 'quantity',
    ];
}

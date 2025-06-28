<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Auth;

class SummaryProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'description',
        'customer_id',
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
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withTrashed();
    }
}

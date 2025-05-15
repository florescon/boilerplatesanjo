<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;

class ProductionReceive extends Model
{
    use HasFactory;

    protected $fillable = ['quantity', 'received_at', 'audi_id'];
    
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    public function receivable()
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'is_active',
    ];

    public function routes()
    {
        return $this->hasMany(ProcessRoute::class);
    }

    public function operations()
    {
        return $this->belongsToMany(
            Operation::class,
            'process_routes'
        )->withPivot('sequence');
    }
}

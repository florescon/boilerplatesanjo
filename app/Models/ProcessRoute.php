<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'process_id',
        'operation_id',
        'sequence',
    ];

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

}

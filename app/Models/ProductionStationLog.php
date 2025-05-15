<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;

class ProductionStationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id', 'station_name', 
        'started_at', 'completed_at',
        'status', 'notes', 'audi_id', 
        'personal_id', 'status_id',
    ];
    
    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }


    public function personal()
    {
        return $this->belongsTo(User::class, 'personal_id')->withTrashed();
    }

    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    public function status()
    {
        return $this->belongsTo(Status::class)->withTrashed();
    }

    public function productionReceives()
    {
        return $this->morphMany(ProductionReceive::class, 'receivable');
    }
}

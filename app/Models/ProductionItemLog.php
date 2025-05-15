<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_item_id', 'station_log_id',
        'input_quantity', 'output_quantity',
        'active',
        'status', 'notes'
    ];
    
    public function batchItem()
    {
        return $this->belongsTo(ProductionBatchItem::class, 'batch_item_id');
    }
    
    public function stationLog()
    {
        return $this->belongsTo(ProductionStationLog::class, 'station_log_id');
    }
}

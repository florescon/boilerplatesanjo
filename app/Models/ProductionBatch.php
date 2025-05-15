<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductionBatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id', 
        'product_id', 
        'is_batch', 
        'is_supplier', 
        'consumption', 
        'is_principal', 
        'notes', 
        'audi_id', 
        'personal_id', 
        'with_previous',
        'date_entered', 
        'status_id'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_batch' => 'boolean',
        'is_supplier' => 'boolean',
        'is_principal' => 'boolean',
        'consumption' => 'boolean',
        'date_entered' => 'date',
    ];


    protected static function booted()
    {
        static::creating(function ($batch) {
            // Establecer la fecha actual
            $batch->date_entered = Carbon::now()->format('Y-m-d');
            
            $batch->audi_id = Auth::id(); 
            // Asignar el siguiente folio si no se ha proporcionado uno
            if (empty($batch->folio)) {
                $batch->folio = $batch->getLastFolioSkipAttribute();
            }
        });
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return mixed
     */
    public function material_order()
    {
        return $this->hasMany(MaterialOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function items()
    {
        return $this->hasMany(ProductionBatchItem::class, 'batch_id');
    }

    public function getTotalProductsProdAttribute(): int
    {
        return $this->items->sum('input_quantity');
    }
    
     public function getLastFolioSkipAttribute()
    {   
        $lastStation = self::where('status_id', $this->status_id)
            ->orderBy('folio', 'desc')
            ->first();

        // Return the next consecutive number
        return $lastStation ? $lastStation->folio + 1 : 1000;
    }

    public function allItemsAreInactiveAndBalanced(): bool
    {
        // Condición 1: Todos los items deben estar inactivos
        $allInactive = $this->items()->where('active', '!=', 0)->doesntExist();
        
        // Condición 2: Todos deben tener input == output
        $allBalanced = $this->items()->whereColumn('input_quantity', '!=', 'output_quantity')->doesntExist();
        
        return $allInactive && $allBalanced;
    }

    public function allItemsAreBalanced(): bool
    {
        $allBalanced = $this->items()->whereColumn('input_quantity', '!=', 'output_quantity')->doesntExist();
        
        return $allBalanced ?? false;
    }
    

    public function stations()
    {
        return $this->hasMany(ProductionStationLog::class);
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

    public function getTypeStatusAttribute()
    {
        if ($this->is_batch && !$this->is_supplier) {
            return 'batch';
        } elseif (!$this->is_batch && $this->is_supplier) {
            return 'supplier';
        } elseif (!$this->is_batch && !$this->is_supplier) {
            return 'forming';
        } else {
            return 'unknown';
        }
    }

   public function getDateForHumansAttribute()
    {
        return $this->created_at ? $this->created_at->isoFormat('D, MMM, YY') : '';
    }

    public function productionReceives()
    {
        return $this->morphMany(ProductionReceive::class, 'receivable');
    }
}

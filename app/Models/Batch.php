<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use App\Models\Traits\Scope\DateScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use DB;

class Batch extends Model
{
    use HasFactory, DateScope, 
        // SoftDeletes, 
        CascadeSoftDeletes;

    protected $cascadeDeletes = ['children', 'batch_product'];

    protected $fillable = [
        'order_id',
        'process_id',
        'personal_id',
        'date_entered',
        'comment',
        'audi_id',
        'batch_id',
        'batch_parent_id',
        'folio',
        'is_consumption',
        'status_name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date_entered'];

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(self::class, 'batch_id');
    }

    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'batch_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function personal()
    {
        return $this->belongsTo(User::class, 'personal_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function getProcess()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    /**
     * @return mixed
     */
    public function batch_product()
    {
        return $this->hasMany(BatchItem::class)->orderBy('created_at', 'desc');
    }

    public function getTotalBatchAttribute(): int
    {
        return $this->batch_product->sum('quantity');
    }

    public function getTotalBatchDeliveredAttribute(): int
    {
        $maxSequence = DB::table('batch_operations')
            ->where('batch_id', $this->id)
            ->max('sequence');

        return DB::table('batch_operations')
            ->where('batch_id', $this->id)
            ->where('sequence', $maxSequence)
            ->sum('delivered');
    }

    public function getTotalBatchPendingProcessedAttribute(): int
    {
        $minSequence = DB::table('batch_operations')
            ->where('batch_id', $this->id)
            ->min('sequence');

        return (int) DB::table('batch_operations')
            ->where('batch_id', $this->id)
            ->where('sequence', $minSequence)
            ->sum(DB::raw('expected - processed'));
    }

    public function getAverageProcessingTimeAttribute(): string
    {
        $averageSeconds = (int) DB::table('batch_operations')
            ->where('batch_id', $this->id)
            ->selectRaw('COALESCE(AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)), 0) AS average_seconds')
            ->value('average_seconds');

        $days = intdiv($averageSeconds, 86400);
        $hours = intdiv($averageSeconds % 86400, 3600);

        return "{$days} días {$hours} horas";
    }
    
    public function getProgressBatch()
    {
        return DB::table('batch_operations')
            ->selectRaw("
                ROUND((SUM(received) / NULLIF(SUM(expected), 0)) * 100, 2) AS received,
                ROUND((SUM(processed) / NULLIF(SUM(expected), 0)) * 100, 2) AS processed,
                ROUND((SUM(delivered) / NULLIF(SUM(expected), 0)) * 100, 2) AS delivered
            ")
            ->where('batch_id', $this->id)
            ->first();
    }


    public function getUniqueOperation()
    {
        return DB::table('batch_operations')
            ->where('batch_id', $this->id)
            ->selectRaw('MIN(id) as id, operation_name, operation_id, sequence')
            ->groupBy('operation_name')
            ->orderBy('sequence')
            ->get();
    }


    public function operationTotals()
    {
        return DB::table('batch_operations')
            ->where('batch_id', $this->id)
            ->select(
                'operation_id',
                'operation_name',
                DB::raw('SUM(expected) as total_expected'),
                DB::raw('SUM(processed) as total_processed'),
                DB::raw('SUM(received) as total_received'),
                DB::raw('SUM(delivered) as total_delivered')
            )
            ->groupBy(
                'operation_id',
                'operation_name'
            );
    }

public function realStationDistribution()
{
    return DB::table('batch_operations as current')
        ->leftJoin('batch_operations as next', function($join){
            $join->on('next.batch_id', '=', 'current.batch_id')
                 ->on('next.sequence', '=', DB::raw('current.sequence + 1'))
                 ->on('next.product_id', '=', 'current.product_id');
        })
        ->where('current.batch_id', $this->id)
        ->select(
            'current.operation_id',
            'current.operation_name',
            DB::raw('SUM(current.delivered - COALESCE(next.delivered,0)) as real_quantity')
        )
        ->groupBy(
            'current.operation_id',
            'current.operation_name'
        )
        ->orderBy('current.operation_id');
}
    public function getProductSizesFromBatch()
    {
        // Primer producto encontrado en batch_items
        $productId = DB::table('batch_items')
            ->where('batch_id', $this->id)
            ->value('product_id');

        if (!$productId) {
            return collect();
        }

        // Obtener el parent_id del producto
        $parentId = DB::table('products')
            ->where('id', $productId)
            ->value('parent_id');

        if (!$parentId) {
            return collect();
        }

        // Obtener los size_id únicos del mismo parent
        return DB::table('products')
            ->join('sizes', 'products.size_id', '=', 'sizes.id')
            ->where('products.parent_id', $parentId)
            ->select(
                'sizes.id',
                'sizes.name',
                'sizes.sort'
            )
            ->distinct()
            ->orderBy('sizes.sort')
            ->get();
    }


public function getSizesByParent($productParentId)
{
    return DB::table('products')
        ->join('sizes', 'sizes.id', '=', 'products.size_id')
        ->where('products.parent_id', $productParentId)
        ->select(
            'sizes.id',
            'sizes.name',
            'sizes.sort'
        )
        ->distinct()
        ->orderBy('sizes.sort')
        ->get();
}


public function getProductColorsFromBatch()
{
    // Obtener todos los product_id del batch
    $productIds = DB::table('batch_items')
        ->where('batch_id', $this->id)
        ->pluck('product_id');

    if ($productIds->isEmpty()) {
        return collect();
    }

    // Obtener los parent_id únicos de esos productos
    $parentIds = DB::table('products')
        ->whereIn('id', $productIds)
        ->pluck('parent_id')
        ->filter()
        ->unique();

    // return $parentIds; 
       
    if ($parentIds->isEmpty()) {
        return collect();
    }

    // Obtener los color_id únicos de todos los productos con esos parent_id
    return DB::table('products')
        ->join('colors', 'products.color_id', '=', 'colors.id')
        ->whereIn('products.id', $productIds)
        ->select(
            'colors.id',
            'colors.name',
            'colors.sort'
        )
        ->distinct()
        ->orderBy('colors.sort')
        ->get();
}


    public function getTotalBatchReceivedAttribute(): int
    {
        return $this->batch_product->sum(function($batch_product) {
          return $batch_product->sum('quantity');
        });
    }

    public function getTotalBatchedAttribute(): int
    {
        return $this->children->sum(function($children) {
          return $children->batch_product->sum('quantity');
        });
    }

    public function getFolioOrIDAttribute()
    {
        if($this->folio !== 0)
            return $this->folio;

        return $this->batch_parent_id;
    }

    public function getParentOrIDAttribute()
    {
        if($this->batch_id !== null)
            return $this->parent->folio_or_id;

        return $this->folio;
    }

    public function isPending(): bool
    {
        return $this->batch_product->where('active', 0)->count();
    }

    public function isTotal()
    {
        return $this->batch_product->sum(function($parent) {
          return $parent->active;
        });
    }

    public function getLastFolioBatchAttribute(): int
    {   
        $firstStatusBatch = \App\Models\Status::firstStatusBatch();

        if(!$this->status->process){
            if($this->batch_id && ($firstStatusBatch->id !== $this->status_id)){
                return  0;
            }
            else{
                $batch = DB::table('batches')->where('folio', '<>', 0)->where('deleted_at', null)->latest()->first();

                if($batch){
                    return $batch->folio ? $batch->folio + 1 : $this->id;
                }

                return $this->id;
            }
        }
        else {
            return 0;
        }

        return $this->id;
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at->isoFormat('D, MMM, YY');
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    public function getDateDiffForHumansCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}

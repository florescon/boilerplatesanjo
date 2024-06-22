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
    use HasFactory, DateScope, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['children', 'batch_product'];

    protected $fillable = [
        'order_id',
        'status_id',
        'personal_id',
        'date_entered',
        'comment',
        'audi_id',
        'batch_id',
        'batch_parent_id',
        'folio',
        'is_consumption',
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

    public function status()
    {
        return $this->belongsTo(Status::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function batch_product()
    {
        return $this->hasMany(BatchProduct::class)->orderBy('created_at', 'desc');
    }

    public function getTotalBatchAttribute(): int
    {
        return $this->batch_product->sum('quantity');
    }

    public function getTotalBatchReceivedAttribute(): int
    {
        return $this->batch_product->sum(function($batch_product) {
          return $batch_product->received->sum('quantity');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Scope\DateScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Domains\Auth\Models\User;

class Station extends Model
{
    use HasFactory, DateScope, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['product_station', 'children', 'material_order', 'station_preconsumption'];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'order_id',
        'status_id',
        'personal_id',
        'comment',
        'date_entered',
        'created_audi_id',
        'last_modified_audi_id',
        'station_id',
        'active',
        'consumption',
        'folio',
        'invoice',
        'service_type_id',
        'invoice_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_entered' => 'date',
        'invoice_date' => 'date',
    ];

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

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function product_station()
    {
        return $this->hasMany(ProductStation::class)->orderBy('created_at', 'desc');
    }

    public function product_station_deleted()
    {
        return $this->hasMany(ProductStation::class)->onlyTrashed()->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function station_preconsumption()
    {
        return $this->hasMany(StationPreconsumption::class);
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(self::class, 'station_id');
    }

    /**
     * @return mixed
     */
    public function material_order()
    {
        return $this->hasMany(MaterialOrder::class, 'station_id');
    }

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'created_audi_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function personal()
    {
        return $this->belongsTo(User::class, 'personal_id')->withTrashed();
    }

    /**
     * @return bool
     */
    public function hasBatch(): bool
    {
        return $this->status->batch ?? false;
    }

    /**
     * @return bool
     */
    public function hasInitialLot(): bool
    {
        return $this->status->initial_lot ?? false;
    }

    /**
     * @return bool
     */
    public function hasInitialProcess(): bool
    {
        return $this->status->initial_process ?? false;
    }


    /**
     * @return bool
     */
    public function hasProcess(): bool
    {
        return $this->status->process ?? false;
    }

    /**
     * @return string
     */
    public function getLineThroughAttribute()
    {
        if(!$this->active){
            return 'text-decoration-line-through';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getCardSecondaryAttribute()
    {
        if(!$this->active){
            return 'bg-secondary';
        }

        return '';
    }

    public function getLastFolioSkipAttribute()
    {   
        $lastStation = self::where('status_id', $this->status_id)
            ->orderBy('folio', 'desc')
            ->first();

        // Return the next consecutive number
        return $lastStation ? $lastStation->folio + 1 : 1;
    }

    public function getTotalProductsStationAttribute(): int
    {
        return $this->product_station->sum('quantity');
    }

    public function getTotalProductsStationDeletedAttribute(): int
    {
        return $this->product_station_deleted->sum('quantity');
    }

    public function getTotalProductsStationOpenAttribute(): int
    {
        return $this->product_station->sum('metadata.open');
    }

    public function getTotalProductsStationClosedAttribute(): int
    {
        return $this->product_station->sum('metadata.closed');
    }

    public function getTotalProductsStationOpenDeletedAttribute(): int
    {
        return $this->product_station_deleted->sum('metadata.open');
    }

    public function getTotalProductsStationClosedDeletedAttribute(): int
    {
        return $this->product_station_deleted->sum('metadata.closed');
    }

    public function getCreatedAtForHumansAttribute()
    {
        return $this->created_at->isoFormat('D, MMM, YYYY H:mm');
    }

    /**
     * Get the difference between created_at and updated_at.
     *
     * @return array
     */
    public function getDifferenceForHumans()
    {
        $createdAt = Carbon::parse($this->created_at);
        $updatedAt = Carbon::parse($this->updated_at);

        if($createdAt == $updatedAt){
            return __('Without changes');
        }

        return $createdAt->diffForHumans($updatedAt, true);
    }

    public function getDateFormattedAttribute()
    {
        return $this->created_at->isoFormat('DD/MM/YY');
    }

    public function getInvoiceDateFormatAttribute()
    {
        return !$this->invoice_date ? '' : $this->invoice_date->isoFormat('D, MMM, YY');
    }

    public function getElapsedForHumans()
    {
        $createdAt = Carbon::parse($this->created_at);

        return $createdAt->diffForHumans(now(), true);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Scope\DateScope;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class ProductStation extends Model
{
    use HasFactory, DateScope, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['product_station_receiveds', 'product_station_outs', 'children'];

    protected $dates = ['deleted_at'];

    protected $getStatus;

    protected $fillable = [
        'order_id',
        'station_id',
        'product_id',
        'product_order_id',
        'status_id',
        'personal_id',
        'product_station_id',
        'quantity',
        'metadata',
        'metadata->open',
        'metadata->closed',
        'metadata_product_station',
        'metadata_extra',
        'comment',
        'created_audi_id',
        'active',
        'not_consider',
    ];

    protected $touches = ['station'];

    /**
     * @return mixed
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * @return mixed
     */
    public function personal()
    {
        return $this->belongsTo(User::class, 'personal_id')->withTrashed();
    }

    public function getOpenAttribute()
    {
        return $this->metadata->open;
    }

    public function getClosedAttribute()
    {
        return $this->metadata->closed;
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->with('parent', 'color', 'size')->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function product_order()
    {
        return $this->belongsTo(ProductOrder::class)->withTrashed();
    }

    public function getQuantityBelongTo()
    {
        return $this->product_id ? $this->product_order->quantity : 0; 
    }

    /**
     * @return mixed
     */
    public function product_station_receiveds()
    {
        return $this->hasMany(ProductStationReceived::class)->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function product_station_outs()
    {
        return $this->hasMany(ProductStationOut::class)->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(self::class, 'product_station_id');
    }

    //Quantities by Status Open
    public function getQuantitiesByStatusOpen($status_id): int
    {
        $totals = self::where('product_station_id', $this->id)
            ->where('status_id', $status_id)
            ->where('order_id', $this->order_id)
            ->where('product_id', $this->product_id)
            ->where('active', '=', 1)
            ->sum('metadata->open');

        return $totals;
    }

    //Quantities by Status Closed
    public function getQuantitiesByStatusClosed($status_id): int
    {
        $totals = self::where('product_station_id', $this->id)
            ->where('status_id', $status_id)
            ->where('order_id', $this->order_id)
            ->where('product_id', $this->product_id)
            ->where('active', '=', 1)
            ->sum('metadata->closed');

        return $totals;
    }

    public function getAvailableBatchAttribute()
    {
        return $this->metadata['closed'];
    }

    public function getStatus($statusId)
    {   
        if (!$this->getStatus) {
            $this->getStatus = Status::findOrFail($statusId);
        }

        return $this->getStatus;
    }

    public function getAvailableBatch($status_id, $station_id): int
    {
        $status = $this->getStatus($status_id);

        $getStatusPrevious = Status::where('level', '<', $status->level)->whereActive(true)
                ->latest('level')
                ->first();

        if($getStatusPrevious->initial_lot){
            $totals = self::where('status_id', $getStatusPrevious->id)
                ->where('order_id', $this->order_id)
                ->where('station_id', $station_id)
                ->where('product_id', $this->product_id)
                ->where('active', '=', 1)
                ->sum('metadata->closed');
        }
        else{
            $totals = self::where('status_id', $getStatusPrevious->id)
                ->where('order_id', $this->order_id)
                ->where('product_id', $this->product_id)
                ->where('active', '=', 1)
                ->where('product_station_id', $this->id ?? null)
                ->sum('metadata->closed');
        }
        return $totals;

        // return $this->metadata['closed'];
    }

    public function getAvailableProcess($status_id): int
    {
        $status = $this->getStatus($status_id);

        $getStatusPrevious = Status::where('level', '<', $status->level)->whereActive(true)
                ->latest('level')
                ->first();

        $totals = self::where('status_id', $getStatusPrevious->id)
            ->where('order_id', $this->order_id)
            ->where('product_id', $this->product_id)
            ->where('active', '=', 1)
            ->sum('metadata->closed');

        return $totals;

        // return $this->metadata['closed'];
    }

    public function getAvailableInitialProcess($status_id)
    {
        $status = $this->getStatus($status_id);

        // Obtener todos los estados previos en orden descendente de nivel
        $previousStatuses = Status::where('level', '<', $status->level)
            ->where('active', true)
            ->where('process', true)
            ->orderBy('level', 'desc')
            ->get();

        $getStatusPrevious = Status::where('level', '<', $status->level)->whereActive(true)->whereProcess(true)
                ->latest('level')
                ->first();


        $totals = 0;

        // Iterar sobre los estados previos hasta encontrar uno que no tenga 'not_restricted'
        foreach ($previousStatuses as $previousStatus) {
            if (!$previousStatus->not_restricted) {

                $productStation = self::where('status_id', $previousStatus->id)
                    ->where('order_id', $this->order_id)
                    ->where('product_id', $this->product_id)
                    ->where('active', '=', 1)
                    ->sum('metadata->closed');
                    ;


                return $productStation;
            }
        }

        return 0;
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

    public function getCreatedAtForHumansAttribute()
    {
        return $this->created_at->isoFormat('D, MMM, YYYY H:mm');
    }

    public function getUpdatedAtForHumansAttribute()
    {
        return $this->updated_at->isoFormat('D, MMM, YYYY H:mm');
    }

    /*
    public static function findTotalOpened()
    {
        return self::select(DB:raw('sum("json_extract('metadata', '$.open')") as total_opened'))->get();
    }
    */
    
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($productStation) {
            $statusId = $productStation->status_id; /* obtener el $status_id adecuado */;
            $quantitiesClosed = $productStation->getQuantitiesByStatusClosed($statusId);

            if ($productStation->metadata['closed'] == 0 && $quantitiesClosed ==  0) {
                // Actualizar el atributo 'active' de la estación relacionada a false

                $sumClosed = 0;
                $sumOpen = 0;
                $station = Station::find($productStation->station_id);
                foreach($station->product_station as $product_station){
                    $sumClosed += $product_station->metadata['closed'];
                    $sumOpen += $product_station->metadata['open'];
                }
                if(($sumClosed == 0) && ($sumOpen == 0)){
                    $station = Station::find($productStation->station_id);
                    $station->active = false;
                    $station->save();
                }
            }
        });
    }

    protected $casts = [
        'metadata' => 'json',
        'metadata_product_station' => 'json',
        'metadata_extra' => 'json',
    ];

}

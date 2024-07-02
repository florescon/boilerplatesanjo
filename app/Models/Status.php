<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'description', 
        'level', 
        'percentage', 
        'to_add_users',
        'initial_process',
        'final_process',
        'supplier',
        'initial_lot',
        'final_lot',
        'active',
        'making',
    ];

    /**
     * Define if an to_add_users is enabled or not.
     *
     * @return bool
     */
    public function toAddUsers(): bool
    {
        return $this->to_add_users === true;
    }

    public function firstStatusBatch()
    {
        return self::where('batch', TRUE)->orderBy('level')->first();        
    }
    public function lastStatusBatch()
    {
        return self::where('batch', TRUE)->orderBy('level', 'desc')->first();        
    }

    public function firstStatusProcess()
    {
        return self::where('process', TRUE)->orderBy('level')->first();        
    }

    public function lastStatusProcess()
    {
        return self::where('process', TRUE)->orderBy('level', 'desc')->first();        
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getStatusAddUsersAttribute()
    {
        if($this->to_add_users){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-secondary'>".__('No').'</span>';
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getStatusBatchAttribute()
    {
        if($this->batch){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-secondary'>".__('No').'</span>';
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getStatusAutomaticAttribute()
    {
        if($this->automatic){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-secondary'>".__('No').'</span>';
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getStatusNotRestrictedAttribute()
    {
        if($this->not_restricted){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-secondary'>".__('No').'</span>';
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getStatusProcessAttribute()
    {
        if($this->process){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-secondary'>".__('No').'</span>';
    }

    public function getStatusActiveAttribute()
    {
        if($this->active){
            return "<span class='badge badge-success'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-secondary'>".__('No').'</span>';
    }

    public function getStatusSupplierAttribute()
    {
        if($this->supplier){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-secondary'>".__('No').'</span>';
    }

    public function getStatusMakingAttribute()
    {
        if($this->making){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-secondary'>".__('No').'</span>';
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at ? $this->updated_at->isoFormat('D, MMM, YY') : $this->updated_at;
    }

    public function getAllQuantitiesByStatusOpenedDefined($order_id)
    {
        $totals = DB::table('product_stations')
            ->where('status_id', $this->id)
            ->where('order_id', $order_id)
            ->where('active', '=', 1)
            ->where('deleted_at', null)
            ->sum('metadata->open');

        return $totals;
    }

    public function getAllQuantitiesByStatusClosedDefined($order_id)
    {
        $totals = DB::table('product_stations')
            ->where('status_id', $this->id)
            ->where('order_id', $order_id)
            ->where('active', '=', 1)
            ->where('deleted_at', null)
            ->sum('metadata->closed');

        return $totals;
    }

    public function getAllQuantitiesByStatusDefined($order_id)
    {
        $totals = DB::table('product_stations')
            ->where('status_id', $this->id)
            ->where('order_id', $order_id)
            ->where('deleted_at', null)
            ->sum('quantity');

        return $totals;
    }

    public function getAllQuantitiesByStatusOpened()
    {
        $totals = DB::table('product_stations')
            ->where('status_id', $this->id)
            ->where('active', '=', 1)
            ->where('deleted_at', null)
            ->sum('metadata->open');

        return $totals;
    }

    public function getAllQuantitiesByStatusClosed()
    {
        $totals = DB::table('product_stations')
            ->where('status_id', $this->id)
            ->where('active', '=', 1)
            ->where('deleted_at', null)
            ->sum('metadata->closed');

        return $totals;
    }

    public function getButtonsLabelsAttribute()
    {
        $details = [];

        if($this->supplier){
            $details[0] = '<a type="button" href="'.route('admin.information.status.show', $this->id).'" class="stretched-link btn btn-warning btn-sm rounded-pill">'.__('Provider').'</a> ';
        }        

        if($this->batch){
            $details[1] = '<a type="button" href="'.route('admin.information.status.show', $this->id).'" class="stretched-link btn btn-primary btn-sm rounded-pill">'.__('Lote').'</a> ';
        }
        if($this->process){
            $details[2] = '<a type="button" href="'.route('admin.information.status.show', $this->id).'" class="stretched-link btn btn-success btn-sm rounded-pill">Proceso</a> ';
        }

        if($this->initial_lot){
            $details[3] = '<a type="button" href="'.route('admin.information.status.show', $this->id).'" class="stretched-link btn btn-secondary btn-sm rounded-pill">Inicial lote</a> ';
        }
        if($this->final_lot){
            $details[4] = '<a type="button" href="'.route('admin.information.status.show', $this->id).'" class="stretched-link btn btn-secondary btn-sm rounded-pill">Final lote</a> ';
        }
        if($this->initial_process){
            $details[5] = '<a type="button" href="'.route('admin.information.status.show', $this->id).'" class="stretched-link btn btn-secondary btn-sm rounded-pill">Inicial proceso</a> ';
        }
        if($this->final_process){
            $details[6] = '<a type="button" href="'.route('admin.information.status.show', $this->id).'" class="stretched-link btn btn-secondary btn-sm rounded-pill">Final proceso</a> ';
        }

        return $details;
    }

    public function getPreviousStatus()
    {
        return self::where('level', '<', $this->level)->whereActive(true)
                ->latest('level')
                ->first();
    }

    public function getNextStatus()
    {
        return self::where('level', '>', $this->level)->whereActive(true)
                ->oldest('level')
                ->first();
    }

    public function getPreviousStatusLot()
    {
        $previous_status_lot = '';

        if($this->batch){
            $previous_status_lot = self::where('level', '<', $this->level)->whereBatch(true)->whereActive(true)
                ->latest('level')
                ->first();
        }

        return $previous_status_lot;
    }

    public function getNextStatusLot()
    {
        $next_status_lot = '';

        if($this->batch){
            $next_status_lot = self::where('level', '>', $this->level)->whereBatch(true)->whereActive(true)
                ->oldest('level')
                ->first();
        }

        return $next_status_lot;
    }

    public function getPreviousStatusProcess()
    {
        $previous_status_process = '';

        if($this->process){
            $previous_status_process = self::where('level', '<', $this->level)->whereProcess(true)->whereActive(true)
                ->latest('level')
                ->first();
        }

        return $previous_status_process;
    }

    public function getNextStatusProcess()
    {
        $next_status_process = '';

        if($this->process){
            $next_status_process = self::where('level', '>', $this->level)->whereProcess(true)->whereActive(true)
                ->oldest('level')
                ->first();
        }

        return $next_status_process;
    }

    public function getSupplierStatus()
    {
        $status_supplier = self::whereSupplier(true)->whereActive(true)
            ->first();

        return $status_supplier;
    }

    public function getFirstStatusLot()
    {
        $first_status_lot = self::whereProcess(true)->whereActive(true)->whereInitiaLot(true)
            ->first();

        return $first_status_lot;
    }

    public function getFirstStatusProcess()
    {
        $first_status_process = self::whereProcess(true)->whereActive(true)->whereInitialProcess(true)
            ->first();

        return $first_status_process;
    }


    public function getDataLogicAttribute(): string
    {
        switch (true) {
                case $this->final_lot && $this->batch:
                    return 'Final del Lote, se envia directo a Conformado';
                case $this->supplier:
                    return 'Pedido a Proveedor se envia directo a Conformado';
                case $this->final_process && $this->process:
                    return 'Se concluye Pedido';
                case $this->initial_process && $this->process:
                    return 'Proveniente de Pedidos a Proveedor, Lotes y/o Almacén Producto Terminado';
                case $this->initial_lot && $this->batch:
                    return 'Se crea el Lote';
                case $this->batch:
                    return 'Estación de Loteado';
                case $this->process:
                    return 'Estación de Proceso';
                default:
                    return __('Good');
            }

        return '';
    }

    public function isNextAutomatic(){
        return $this->getNextStatus()->automatic;
    }

    public function getDataStatus()
    {
        $data = array();

        switch ($this->active) {
            case $this->batch:
                $data[] = __('Lot');

                if($this->initial_lot){
                    $data[] = __('Initial lot ');
                }
                if($this->final_lot){
                    $data[] = __('Final lot ');
                    if($this->getFirstStatusProcess()){
                        $data[] = __('Send auto to Initial Process');
                    }
                }

                return $data;
            case $this->supplier:
                $data[] = __    ('Supplier ');
                if($this->getFirstStatusProcess()){
                    $data[] = __('Send auto to Initial Process');
                }

                return $data;
            case $this->process:
                $data[] = __('Process');

                if($this->initial_process){
                    $data[] = __('Initial Process ');
                }
                if($this->final_process){
                    $data[] = __('Final Process ');
                }

                return $data;
        }

        return $data;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'to_add_users' => 'boolean',
    ];
}

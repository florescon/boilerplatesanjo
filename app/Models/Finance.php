<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FinanceType;
use App\Models\Traits\Scope\FinanceScope;
use App\Models\Traits\Scope\DateScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Finance extends Model
{
    use HasFactory, SoftDeletes, FinanceScope, DateScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'order_id',
        'user_id',
        'amount',
        'comment',
        'ticket_text',
        'type',
        'date_entered',
        'from_store',
        'payment_method_id',
        'audi_id',
        'cash_id',
        'is_bill',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'from_store' => 'boolean',
        'is_bill' => 'boolean',
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
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
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
    public function departament()
    {
        return $this->belongsTo(Departament::class)->withTrashed();
    }

    /**
     * Return the correct order status formatted.
     *
     * @return mixed
     */
    public function getUserNameOrDepartamentAttribute(): string
    {
        if($this->user_id){
            return $this->user->name;
        }
        elseif($this->departament_id){
           return $this->departament->name;
        }

        return '';
    }

    /**
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function cash()
    {
        return $this->belongsTo(Cash::class)->withTrashed();
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id')->withTrashed();
    }

    public function getPaymentMethodAttribute(): ?string
    {
        if ($this->payment_method_id !== null) {
            return $this->payment->short_title ?? '-- '.__('undefined payment').' --';
        }

        return '-- '.__('undefined payment').' --';
    }

    public function getOrderTrackAttribute(): ?string
    {
        if($this->order_id !== null){
            return $this->order ? '<a href="'.route('admin.order.edit', $this->order_id).'"><strong>'.$this->order->slug.'</strong></a>' : "<span class='badge badge-secondary'>".__('undefined order/sale').'</span>';
        }
        return "<span class='badge badge-secondary'>".__('undefined order/sale').'</span>';
    }

    public function getOrderFolioAttribute(): ?string
    {
        if($this->order_id !== null){
            return $this->order ? '<a href="'.route('admin.order.edit', $this->order_id).'"><strong> #'.$this->order->id.'</strong></a>' : "<span class='badge badge-secondary'>".__('undefined order/sale').'</span>';
        }
        return "<span class='badge badge-secondary'>".__('undefined order/sale').'</span>';
    }

    public function getUserNameAttribute(): ?string
    {
        if($this->user_id !== null){
            return $this->user ? "<span class='badge badge-dark'>".$this->user->name.'</span>' : "<span class='badge badge-secondary'>".__('undefined user').'</span>';
        }
        return "<span class='badge badge-secondary'>".__('undefined user').'</span>';
    }

    public function getCashTitleAttribute(): ?string
    {
        if($this->cash_id !== null){
            return $this->cash ? "<span class='badge badge-dark'>".__('Daily cash closing').': '.$this->cash->id.'</span>' : "<span class='badge badge-secondary'>".__('undefined').'</span>';
        }
        return "<span class='badge badge-secondary'>".__('undefined').'</span>';
    }

    public function getDetailsAttribute(): ?string
    {
        $order_id = $this->order_id ? '<strong>'.$this->order->type_order_clear.' #'.$this->order_id.'</strong>' : '';
        
        if($order_id !== null){
            $user = $this->order ? Str::limit(optional($this->order->user)->name, 20) : '';
            $comment = Str::limit(optional($this->order)->comment, 20) ?? '';
        }

        if($this->created_at){
            $created = '<strong>'.$this->date_for_humans_created.'</strong>';            
        }

        return $order_id.' '.$user.' '.$comment.' '.$created;
    }

    public function getDetailsClearAttribute(): ?string
    {
        $order_id = $this->order_id ? $this->order->type_order_clear.' #'.$this->order_id : '';
        
        if($order_id !== null){
            $user = $this->order ? Str::limit(optional($this->order->user)->name, 100) : '';
            $comment = Str::limit(optional($this->order)->comment, 100) ?? '';
        }

        if($this->created_at){
            $created = $this->date_for_humans_created;            
        }

        return $order_id.' '.$user.' '.$comment.' '.$created;
    }

    /**
     * Return status style classes.
     *
     * @return string
     */
    public function getFinanceClassesAttribute(): string
    {
        switch ($this->type) {
            case FinanceType::INCOME:
                return 'bg-primary';
            case FinanceType::EXPENSE:
                return 'bg-danger';
        }

        return '';
    }

    /**
     * Return status style classes.
     *
     * @return string
     */
    public function getFinanceTextAttribute(): string
    {
        switch ($this->type) {
            case FinanceType::INCOME:
                return 'text-primary';
            case FinanceType::EXPENSE:
                return 'text-danger';
        }

        return '';
    }


    /**
     * Return status style classes.
     *
     * @return string
     */
    public function getFinanceSignAttribute(): string
    {
        switch ($this->type) {
            case FinanceType::INCOME:
                return '';
            case FinanceType::EXPENSE:
                return '-';
        }

        return '';
    }

    /**
     * Return the correct order status formatted.
     *
     * @return mixed
     */
    public function getFormattedTypeAttribute(): string
    {
        return FinanceType::values()[$this->type] ?? '';
    }

    /**
     * @return bool
     */
    public function isFromStore(): bool
    {
        return $this->from_store;
    }

    /**
     * Determine if on finance is in income type.
     *
     * @return bool
     */
    public function isIncome(): bool
    {
        return $this->type === FinanceType::INCOME;
    }

    /**
     * Determine if on finance is in expense type.
     *
     * @return bool
     */
    public function isExpense(): bool
    {
        return $this->type === FinanceType::EXPENSE;
    }

    /**
     * Cashable.
     */
    public function cashes()
    {
        return $this->morphMany(Cashable::class, 'cashable');
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at->isoFormat('D, MMM, YY');
    }

    public function getDateForHumansCreatedAttribute()
    {
        return $this->created_at->isoFormat('D, MMM, YY');
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    public function getDateDiffForHumansCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIsBillLabelAttribute()
    {
        if($this->is_bill){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-dark'>".__('No').'</span>';
    }
}

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
        'payment_method_id',
        'audi_id',
        'cash_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'from_store' => 'boolean',
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
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
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
            return $this->order ? "<span class='badge badge-dark'>".$this->order->slug.'</span>' : "<span class='badge badge-secondary'>".__('undefined order/sale').'</span>';
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
        return $this->updated_at->format('M, d Y');
    }

    public function getDateForHumansCreatedAttribute()
    {
        return $this->created_at->format('M, d Y');
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

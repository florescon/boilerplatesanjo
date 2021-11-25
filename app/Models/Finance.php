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
        'payment_method_id',
        'audi_id',
        'cash_id',
    ];

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
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

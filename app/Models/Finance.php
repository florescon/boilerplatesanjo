<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FinanceType;
use App\Models\Traits\Scope\FinanceScope;
use App\Models\Traits\Scope\DateScope;

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
        'amount',
        'comment',
        'ticket_text',
        'type',
        'audi_id',
    ];

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
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

    public function getDateForHumansAttribute()
    {
        return $this->updated_at->format('M, d Y');
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->updated_at->diffForHumans();
    }
}

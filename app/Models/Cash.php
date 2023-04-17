<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Scope\DateScope;
use Carbon\Carbon;
use App\Models\FinanceType;
use App\Domains\Auth\Models\User;

class Cash extends Model
{
    use HasFactory, SoftDeletes, DateScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'comment',
        'initial',
        'total',
        'audi_id',
        'checked',
    ];

    /**
     * @return mixed
     */
    public function finances()
    {
        return $this->hasMany(Finance::class)->with('payment', 'order.user')->withTrashed()->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function orders()
    {
        return $this->hasMany(Order::class)->with('products', 'user')->withTrashed()->orderBy('created_at', 'desc');
    }

    public function getIncomesAttribute()
    {
        return $this->finances->where('type', FinanceType::INCOME);
    }

    public function getExpensesAttribute()
    {
        return $this->finances->where('type', FinanceType::EXPENSE);
    }

    public function getTotalIncomesLabelAttribute()
    {
        if($this->incomes->count()){
            return "<span class='badge badge-primary'>".__('Incomes').': &nbsp;'.$this->incomes->count().'</span>';
        }

        return '';
    }

    public function getTotalExpensesLabelAttribute()
    {
        if($this->expenses->count()){
            return "<span class='badge badge-danger'>".__('Expenses').': &nbsp;'.$this->expenses->count().'</span>';
        }

        return '';
    }

    public function getAmountIncomesAttribute()
    {
        return $this->incomes->sum('amount');
    }

    public function getAmountExpensesAttribute()
    {
        return $this->expenses->sum('amount');
    }

    public function getAmountIncomesCashAttribute()
    {
        return $this->incomes->where('payment_method_id', 1)->sum('amount');
    }

    public function getAmountExpensesCashAttribute()
    {
        return $this->expenses->where('payment_method_id', 1)->sum('amount');
    }

    public function getAmountIncomesDifferentCashAttribute()
    {
        return $this->incomes->where('payment_method_id', '<>', 1)->sum('amount');
    }

    public function getAmountExpensesDifferentCashAttribute()
    {
        return $this->expenses->where('payment_method_id', '<>', 1)->sum('amount');
    }

    public function getTotalAmountCashFinancesAttribute()
    {
        return number_format($this->amount_incomes_cash - $this->amount_expenses_cash, 2);
    }

    public function getTotalAmountCashDifferentFinancesAttribute()
    {
        return number_format($this->amount_incomes_different_cash - $this->amount_expenses_different_cash, 2);
    }

    public function getTotalAmountFinancesAttribute()
    {
        return $this->amount_incomes - $this->amount_expenses;
    }

    public function getDailyCashClosingAttribute()
    {
        return number_format($this->total_amount_finances, 2);
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

    public function getDateDiffForHumansCheckedAttribute()
    {
        return $this->checked->diffForHumans();
    }

    /**
     * @return bool
     */
    public function lastDay(): bool
    {
        if($this->checked->gt(Carbon::now()->subDay())){
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function getlastDayAttribute()
    {
        return $this->lastDay();
    }

    /**
     * @return string
     */
    public function getLastDayLabelAttribute()
    {
        return $this->lastDay() ? '('.__('Available 24 hours').')' : '';
    }

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    protected $dates = ['checked'];
}

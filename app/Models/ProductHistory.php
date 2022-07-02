<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ProductHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_histories';

    /**
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function subproduct()
    {
        return $this->belongsTo(Product::class, 'subproduct_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    public function isOutput(): bool
    {
        return $this->is_output;
    }

    public function isStock(): bool
    {
        return $this->type_stock === 'stock';
    }

    public function isStockRevision(): bool
    {
        return $this->type_stock === 'stock_revision';
    }

    public function isStockStore(): bool
    {
        return $this->type_stock === 'stock_store';
    }

    public function getBalanceAttribute(): int
    {
        switch ($this->isOutput()) {
            case true:
                return $this->old_stock - abs($this->stock);
            case false:
                return $this->old_stock + abs($this->stock);
        }

        return false;
    }

    /**
     * Return type_stock label.
     */
    public function getTypeStockLabelAttribute(): string
    {
        switch ($this->type_stock) {
            case $this->isStock():
                return __('Finished product');
            case $this->isStockRevision():
                return __('Intermediate Review');
            case $this->isStockStore():
                return __('Store product');
        }

        return '';
    }

    /**
     * Return type_stock label.
     */
    public function getTypeStockRelationshipAttribute(): string
    {
        switch ($this->type_stock) {
            case $this->isStock():
                return $this->subproduct->stock ?? 0;
            case $this->isStockRevision():
                return $this->subproduct->stock_revision ?? 0;
            case $this->isStockStore():
                return $this->subproduct->stock_store ?? 0;
        }

        return '';
    }

    public function getIsOutputLabelAttribute()
    {
        return $this->isOutput() ? "<em class='text-danger'>".__('Output').'</em>' : "<em class='text-success'>".__('Input').'</em>';         
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'old_stock', 'stock', 'price', 'old_type_stock', 'type_stock', 'order_id', 'is_output', 'audi_id', 'subproduct_id',
    ];
}

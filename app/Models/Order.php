<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Auth\Models\User;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Traits\Scope\OrderScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\OrderStatusPayment;
use App\Models\OrderStatusDelivery;

class Order extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, OrderScope, Sluggable;

    protected $cascadeDeletes = ['product_order', 'product_sale', 'materials_order'];

    protected $fillable = [
        'date_entered', 
        'cash_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_entered' => 'date',
        'automatic_production' => 'boolean',
        'from_store' => 'boolean',
        'approved' => 'boolean',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'full_slug',
                'onUpdate' => false,
            ]
        ];
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date_entered'];

    public function getFullSlugAttribute(): string
    {
        return 'sju'. ' ' . Str::random(6);
    }

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
    public function departament()
    {
        return $this->belongsTo(Departament::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function audi()
    {
        return $this->belongsTo(User::class, 'audi_id');
    }

    /**
     * Return the correct order status formatted.
     *
     * @return mixed
     */
    public function getUserNameAttribute(): string
    {
        return $this->user_id ? $this->user->name : "<span class='badge badge-primary'>Stock ".appName().'</span>';
    }

    public function getTrackingNumberAttribute(): ?string
    {
        return $this->slug ?? '';
    }

    /**
     * @return mixed
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class)->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function product_order()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size')->where('type', 1);
    }

    /**
     * @return mixed
     */
    public function product_sale()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size')->where('type', 2);
    }

    /**
     * @return mixed
     */
    public function product_suborder()
    {
        return $this->hasMany(ProductOrder::class, 'suborder_id')->with('parent_order.product.parent', 'parent_order.product.color', 'parent_order.product.size');
    }

    /**
     * @return mixed
     */
    public function suborders()
    {
        return $this->hasMany(self::class, 'parent_order_id')->orderBy('created_at', 'desc');
    }

    /**
     * @return bool
     */
    public function isChildren(): ?bool
    {
        return $this->parent_order_id;
    }

    /**
     * @return bool
     */
    public function isNullUserDepartament(): ?bool
    {
        return !$this->user_id && !$this->departament_id;
    }

    /**
     * @return bool
     */
    public function getExistUserDepartamentAttribute(): ?bool
    {
        return $this->isNullUserDepartament();
    }

    public function getParentOrderAttribute(): ?string
    {
        if ($this->isChildren()) {
            return $this->parent_order_id ?? '';
        }

        return '';
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
     * @return mixed
     */
    public function status_order()
    {
        return $this->hasMany(StatusOrder::class)->with('status');
    }

    public function last_status_order()
    {
        return $this->hasOne(StatusOrder::class)->latestOfMany();
    }

    public function getLastStatusOrderLabelAttribute()
    {

        if (!$this->parent_order_id) {
            if(!$this->last_status_order){
                return "<span class='badge badge-secondary'>".__('undefined').'</span>';
            }

            return $this->last_status_order->name_status;

        }

        return "--<em> ".__('not applicable')." </em>--";
    }

    /**
     * @return mixed
     */
    public function materials_order()
    {
        return $this->hasMany(MaterialOrder::class)->with('material');
    }

    public function getTotalProductsAttribute(): int
    {
        return $this->product_order->sum('quantity');
    }

    public function getTotalProductsSaleAttribute(): int
    {
        return $this->product_sale->sum('quantity');
    }

    public function getTotalProductsAssignmentsAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity - $parent->assignments->where('output', 0)->sum('quantity');
        });
    }

    public function getTotalOrderAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getTotalSaleAttribute()
    {
        return $this->product_sale->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getTotalProductsSuborderAttribute(): int
    {
        return $this->product_suborder->sum(function($product) {
          return $product->quantity;
        });
    }

    public function getTotalProductsAllSubordersAttribute(): int
    {
        return $this->suborders->sum(function($suborders) {
          return $suborders->product_suborder->sum('quantity');
        });
    }

    public function getTotalAvailableByProduct($byID)
    {
        return $this->suborders->sum(function($suborders) use ($byID) {
          return $suborders->product_suborder->where('product_id', $byID)->sum('quantity');
        });
    }

    /**
     * @return string
     */
    public function getTypeOrderAttribute()
    {
            switch ($this->type) {
                case 2:
                    return "<span class='badge badge-success'>".__('Sale').'</span>';
                case 3:
                    return "<span class='badge badge-warning text-white'>".__('Mix').'</span>';
                case 4:
                    return "<span class='badge text-white' style='background-color: purple;'>".__('Suborder').'</span>';
                default:
                    return "<span class='badge badge-primary'>".__('Order').'</span>';
            }

        return 'a';
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return bool
     */
    public function isFromStore(): bool
    {
        return $this->from_store;
    }

    /**
     * @return string
     */
    public function getApprovedLabelAttribute()
    {
        if(!$this->parent_order_id){    
            if ($this->isApproved()) {
                return "<span class='badge badge-success'>".__('Approved').'</span>';
            }

            return "<span class='badge badge-danger'>".__('Pending').'</span>';

        }

        return "--<em> ".__('not applicable')." </em>--";
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
        return $this->created_at->format('M, d Y');
    }

    public function getDateDiffForHumansCreatedAttribute()
    {
        return "<span class='badge badge-danger'>".$this->created_at->diffForHumans(null, false, false, 2).'</span>';
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}

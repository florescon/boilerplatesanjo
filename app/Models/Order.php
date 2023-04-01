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
use App\Models\OrdersDelivery;
use DB;

class Order extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, OrderScope, Sluggable;

    protected $cascadeDeletes = ['product_order', 'product_sale', 'product_quotation', 'product_output', 'suborders', 'product_suborder', 'materials_order', 'service_orders'];

    protected $fillable = [
        'date_entered', 
        'cash_id',
        'user_departament_changed_at',
        'feedstock_changed_at',
        'user_id',
        'departament_id',
        'comment',
        'request',
        'purchase',
        'branch_id',
        'type',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'total_payments',
        'total_payments_remaining',
        'payment_label',
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
        'user_departament_changed_at' => 'datetime',
        'feedstock_changed_at' => 'datetime',
        'to_stock' => 'boolean',
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
    protected $dates = [
        'date_entered',
        'user_departament_changed_at',
        'feedstock_changed_at',
    ];

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
        return $this->belongsTo(User::class, 'audi_id')->withTrashed();
    }

    /**
     * Return the correct order status formatted.
     *
     * @return mixed
     */
    public function getUserNameAttribute(): string
    {
        if($this->user_id){
            return $this->user->name;
        }
        elseif($this->departament_id){
           return $this->departament->name;
        }
        elseif($this->isFromStore()){
            return "<span class='badge badge-primary'>General".'</span>';
        }

        return "<span class='badge badge-primary'>".__('Internal control').'</span>';
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

    public function last_ticket()
    {
        return $this->hasOne(Ticket::class)->latestOfMany();
    }

    public function last_ticket_updated()
    {
        return $this->hasOne(Ticket::class)->latestOfMany()->where('updated_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function orders_delivery()
    {
        return $this->hasMany(OrdersDelivery::class)->with('user');
    }

    public function last_order_delivery()
    {
        return $this->hasOne(OrdersDelivery::class)->latestOfMany();
    }

    /**
     * @return mixed
     */
    public function orders_payments()
    {
        return $this->hasMany(Finance::class)->with('audi', 'payment');
    }

    /**
     * @return mixed
     */
    public function last_payment()
    {
        return $this->hasOne(Finance::class)->latestOfMany();
    }

    public function total_payments()
    {
        return $this->orders_payments->sum('amount');
    }

    /**
     * Return Total order price without shipping amount.
     */
    public function getTotalPaymentsAttribute(): string
    {
        return $this->total_payments();
    }

    /**
     * Return Total order price without shipping amount.
     */
    public function getTotalPaymentsRemainingAttribute(): string
    {
        return $this->total_sale_and_order - $this->total_payments();
    }

    /**
     * Return payment label.
     */
    public function getPaymentLabelAttribute()
    {
        if($this->orders_payments()->exists()){
            if($this->total_payments_remaining <= 0){
                return "<span class='badge badge-success'>".__(OrderStatusPayment::PAID).'</span>';
            }
            else{
                return "<span class='badge badge-warning text-white'>".__(OrderStatusPayment::ADVANCED).'</span>';
            }
        }
        else{
            return "<span class='badge badge-danger'>".__('Payment').' '.__(OrderStatusPayment::PENDING).'</span>';
        }
    }

    /**
     * @return mixed
     */
    public function products()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size');
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
    public function product_request()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size')->where('type', 5);
    }

    /**
     * @return mixed
     */
    public function product_quotation()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size')->where('type', 6);
    }

    /**
     * @return mixed
     */
    public function product_output()
    {
        return $this->hasMany(ProductOrder::class)->with('product.parent', 'product.color', 'product.size')->where('type', 7);
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

    public function last_status()
    {
        return DB::table('statuses')->latest('level')->first();
    }

    public function getLastStatusOrderIdAttribute()
    {
        if (!$this->parent_order_id && $this->type != 2) {
            if(!$this->last_status_order){
                return null;
            }

            return $this->last_status_order ? $this->last_status_order->status->id : '';
        }

        return null;
    }

    public function getLastStatusOrderLabelAttribute()
    {
        if (!$this->parent_order_id && $this->type != 2) {
            if(!$this->last_status_order){
                return "<span class='badge badge-secondary'>".__('undefined').'</span>';
            }

            return '<strong>'.$this->last_status_order->name_status.'</strong> - '.$this->last_status_order->date_entered_or_created;
        }

        return "";
    }


    public function getAdvancedOrderLabelAttribute()
    {
        if (!$this->parent_order_id && ($this->type != 6 && $this->type != 7)) {
            return '$'. number_format((float)$this->total_payments, 2);
        }

        return "N/A";
    }

    public function getRemainingOrderLabelAttribute()
    {
        if (!$this->parent_order_id && ($this->type != 6 && $this->type != 7)) {
            return '$'. number_format((float)$this->total_payments_remaining, 2);
        }

        return "N/A";
    }

    public function getLastStatusOrderPercentageAttribute(): int
    {
            if(!$this->last_status_order){
                return 0;
            }

            return $this->last_status_order->percentage_status;
    }

    public function getLastStatusOrderColorAttribute()
    {
            if(!$this->last_status_order){
                return null;
            }

            return $this->last_status_order->color_status;
    }

    /**
     * @return mixed
     */
    public function materials_order()
    {
        return $this->hasMany(MaterialOrder::class)->with('material');
    }

    /**
     * @return mixed
     */
    public function service_orders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function getTotalProductsByAllAttribute(): int
    {
        return $this->products->sum('quantity');
    }

    public function getTotalProductsAttribute(): int
    {
        return $this->product_order->sum('quantity');
    }

    public function getTotalProductsSaleAttribute(): int
    {
        return $this->product_sale->sum('quantity');
    }

    public function getTotalProductsRequestAttribute(): int
    {
        return $this->product_request->sum('quantity');
    }

    public function getTotalProductsQuotationAttribute(): int
    {
        return $this->product_quotation->sum('quantity');
    }

    public function getTotalProductsOutputAttribute(): int
    {
        return $this->product_output->sum('quantity');
    }

    public function getDetailsForBoxAttribute()
    {
        return '<em>'.__('Articles').': '.$this->total_products_by_all.'</em> -- <strong>$'.$this->total_by_all.'</strong>';        
    }

    public function getTotalProductsAssignmentsAttribute()
    {
        return $this->product_order->sum(function($parent) {
          return $parent->quantity - $parent->assignments->where('output', 0)->sum('quantity');
        });
    }

    public function getTotalByAllAttribute()
    {
        return $this->products->sum(function($parent) {
          return $parent->quantity * $parent->price;
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

    public function getTotalRequestAttribute()
    {
        return $this->product_request->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getTotalQuotationAttribute()
    {
        return $this->product_quotation->sum(function($parent) {
          return $parent->quantity * $parent->price;
        });
    }

    public function getTotalSaleAndOrderAttribute(): string
    {
        return $this->total_sale + $this->total_order + $this->total_suborder + $this->total_request + $this->total_quotation;
    }

    public function getTotalArticlesAttribute(): string
    {
        return $this->total_products + $this->total_products_sale + $this->total_products_request + $this->total_products_suborder + $this->total_products_quotation + $this->total_products_output;
    }

    public function getTotalProductsSuborderAttribute(): int
    {
        return $this->product_suborder->sum(function($product) {
          return $product->quantity;
        });
    }

    public function getTotalSuborderAttribute()
    {
        return $this->product_suborder->sum(function($product) {
          return $product->quantity * ($product->price ? $product->price : $product->price);
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
          return $suborders->product_suborder->where('parent_product_id', $byID)->sum('quantity');
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
                    return "<span class='badge text-white' style='background-color: purple;'>".__('Output').'</span>';
                case 5:
                    return "<span class='badge text-white' style='background-color: #2eb85c;'>".__('Request').'</span>';
                case 6:
                    return "<span class='badge text-white' style='background-color: #2eb85c;'>".__('Quotation').'</span>';
                case 7:
                    return "<span class='badge text-white' style='background-color: #2eb85c;'>".__('Output Products').'</span>';
                default:
                    return "<span class='badge badge-primary'>".__('Order').'</span>';
            }

        return '';
    }

    /**
     * @return string
     */
    public function getCharactersTypeOrderAttribute()
    {
            switch ($this->type) {
                case 2:
                    return 'SJU-VEN';
                case 3:
                    return 'SJU-MIX';
                case 4:
                    return 'SJU-OUT';
                case 5:
                    return 'SJU-PED';
                case 6:
                    return 'SJU-COT';
                case 7:
                    return 'SJU-OUTP';
                default:
                    return 'SJU-ORD';
            }

        return '';
    }

    /**
     * @return string
     */
    public function getTypeOrderClearAttribute()
    {
            switch ($this->type) {
                case 2:
                    return __('Sale');
                case 3:
                    return __('Mix: Order/Sale');
                case 4:
                    return __('Output');
                case 5:
                    return __('Request');
                case 6:
                    return __('Quotation');
                case 7:
                    return __('Output Products');
                case 1:
                    return __('Order');
            }

        return '';
    }

    /**
     * @return string
     */
    public function getTypeOrderClassesAttribute()
    {
            switch ($this->type) {
                case 2:
                    return 'background-color:#DEFFDF';
                case 3:
                    return 'background-color: #FFFFDE';
                case 4:
                    return 'background-color: #F7DEFF';
                case 5:
                    return 'background-color: #FFDBD3';
                case 6:
                    return 'background-color: #86FFCF';
                case 7:
                    return 'background-color: #86FFCF';
                default:
                    return 'background-color: #DEE4FF';
            }

        return '';
    }

    public function isOrder(): bool
    {
        return $this->type === 1;
    }

    public function isSale(): bool
    {
        return $this->type === 2;
    }

    public function isMix(): bool
    {
        return $this->type === 3;
    }

    public function isSuborder(): bool
    {
        return $this->type === 4;
    }

    public function isRequest(): bool
    {
        return $this->type === 5;
    }

    public function isQuotation(): bool
    {
        return $this->type === 6;
    }

    public function isOutputProducts(): bool
    {
        return $this->type === 7;
    }

    /**
     * @return bool
     */
    public function isFromStore(): ?bool
    {
        return $this->from_store;
    }

    /**
     * @return bool
     */
    public function isCreatedByUser(): ?bool
    {
        return $this->user_id == $this->audi_id;
    }

    /**
     * @return string
     */
    public function  getFromStoreorUserLabelAttribute()
    {
        if ($this->isFromStore()) {
            return "<span class='badge badge-dark rounded-circle'>".'<i class="fas fa-store"></i>'.'</span>';
        }

        if($this->isCreatedByUser()){
            return "<span class='badge badge-dark'>".'<i class="fas fa-user"></i>'.'</span>';
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return string
     */
    public function getApprovedAlertAttribute()
    {
        if(!$this->isApproved()){
            return '<i class="fas fa-exclamation-triangle" style="color:red;"></i>';
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isToStock(): bool
    {
        return $this->to_stock;
    }

    /**
     * @return string
     */
    public function getToStockFinalAttribute()
    {
        if($this->isToStock()){
            return '<i class="cil-chevron-double-right" style="color:brown;"></i>';
        }

        return '';
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

        return "";
    }

    /**
     * Cashable.
     */
    public function cashes()
    {
        return $this->morphMany(Cashable::class, 'cashable');
    }

    /**
     * @return bool
     */
    public function isDeletedFeedstock(): bool
    {
        return $this->feedstock_changed_at !== null;
    }

    /**
     * @return bool
     */
    public function isUserOrDepartamentReasigned(): bool
    {
        return $this->user_departament_changed_at !== null;
    }

    public function getDateForHumansAttribute()
    {
        return $this->created_at->isoFormat('D, MMM, YYYY');
    }

    public function getDateEnteredOrCreatedAttribute()
    {
        return !$this->date_entered ? $this->created_at->isoFormat('D, MMM') : $this->date_entered->isoFormat('D, MMM');
    }

    public function getDateDiffForHumansCreatedAttribute()
    {
        return $this->created_at->diffForHumans(null, false, false, 2);
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
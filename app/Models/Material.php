<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'part_number',
        'name',
        'description',
        'acquisition_cost',
        'price',
        'stock',
        'unit_id',
        'color_id',
        'size_id',
        'vendor_id',
        'family_id',
        'short_name',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'stock_formatted',
        'full_name_clear',
    ];

    /**
     * Return formatted stock.
     */
    public function getStockFormattedAttribute(): string
    {
        return rtrim(rtrim(sprintf('%.8F', $this->stock), '0'), ".");
    }

    /**
     * Get the color associated with the Material.
     */
    public function color()
    {
        return $this->belongsTo(Color::class)->withTrashed();
    }

    /**
     * Get the size associated with the Material.
     */
    public function size()
    {
        return $this->belongsTo(Size::class)->withTrashed();
    }

    /**
     * Get the unit associated with the Material.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

    /**
     * Get the vendor associated with the Material.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class)->withTrashed();
    }

    /**
     * Get the family associated with the Material.
     */
    public function family()
    {
        return $this->belongsTo(Family::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function history()
    {
        return $this->hasMany(MaterialHistory::class);
    }

    public function material_orders()
    {
        return $this->hasMany(MaterialOrder::class);
    }

    /**
     * @return string
     */
    public function getSizeNameAttribute()
    {
        return $this->size_id ? '| '.$this->size->name : '';
    }

    /**
     * @return string
     */
    public function getUnitNameAttribute()
    {
        return $this->unit_id ? '<sup>'.$this->unit->name.'</sup>' : '';
    }

    /**
     * @return string
     */
    public function getUnitNameLabelAttribute()
    {
        return $this->unit_id ? ($this->unit->abbreviation ?? $this->unit->name) : '';
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return '<strong>'.$this->name.'</strong> '.$this->unit_name.' '.$this->size_name.' '.$this->color_name;
    }

    /**
     * @return string
     */
    public function getFullNameAndCodeAttribute()
    {
        return '<div class="badge badge-primary text-wrap" style="margin-right:10px;"> '. $this->part_number .'</div>'.'<strong>'.$this->name.'</strong> '.$this->unit_name.' '.$this->size_name.' '.$this->color_name;
    }


    /**
     * @return string
     */
    public function getColorNameAttribute()
    {
        return $this->color_id ? '| '.$this->color->name : '';
    }

    /**
     * @return string
     */
    public function getSizeNameClearAttribute()
    {
        return $this->size_id ? ', '.$this->size->name : '';
    }

    /**
     * @return string
     */
    public function getColorNameClearAttribute()
    {
        return $this->color_id ? ', '.$this->color->name : '';
    }

    /**
     * @return string
     */
    public function getUnitNameClearAttribute()
    {
        return $this->unit_id ? ', '.$this->unit->name : '';
    }

    /**
     * @return string
     */
    public function getUnitMeasurementAttribute()
    {
        return $this->unit_id ? ($this->unit->abbreviation ?? $this->unit->name) : '';
    }

    public function getNewMaterialAttribute()
    {
        if($this->created_at){
            if($this->created_at->gt(Carbon::now()->subMonth())){
                return __('New'). ' |';
            }
        }

        return '';
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at ? $this->updated_at->isoFormat('D, MMM, YY') : '';
    }

    /**
     * @return string
     */
    public function getFullNameClearAttribute()
    {
        return $this->name.$this->size_name_clear.$this->color_name_clear;
    }

    public function kardexRecords()
    {
        $materialHistory = $this->history()
            ->with('audi')
            ->get()
            ->map(function ($item) {
                $stock = (float) $item->stock;
                $input = $stock > 0 ? $stock : 0;
                $output = $stock < 0 ? abs($stock) : 0;
                $balance = $item->old_stock + $item->stock;

                return [
                    'user' => optional($item->audi)->initials,
                    'date' => Carbon::parse($item->created_at)->toDateString(),
                    'details' => $item->comment,
                    'cost' => $item->price,
                    'input' => $input,
                    'output' => $output,
                    'instanceof' => true,
                    'balance' => $balance,
                ];
            });

        $materialOrder = $this->material_orders()
            ->with('audi', 'order.user')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->toDateString();
            })
            ->flatMap(function ($itemsByDate, $date) {
                return $itemsByDate->groupBy('order_id')->map(function ($itemsByOrder) use ($date) {
                    $first = $itemsByOrder->first();
                    $totalQuantity = $itemsByOrder->sum('quantity');
                    $avgCost = $itemsByOrder->avg('price');

                    return [
                        'user' => optional($first->audi)->initials,
                        'date' => $date,
                        'details' => optional($first->order)->user_name_clear,
                        'cost' => $avgCost,
                        'input' => 0,
                        'output' => $totalQuantity,
                        'instanceof' => false,
                        'balance' => $first->quantity_old != 0 ? $first->quantity_old + $totalQuantity : '--',
                    ];
                });
            })
            ->values();

        return collect($materialHistory)
            ->merge($materialOrder)
            ->sortByDesc('date')
            ->groupBy('date')
            ->map(function ($items, $date) {
                return [
                    'date' => $date,
                    'records_count' => $items->count(),
                    'items' => $items,
                ];
            });
    }


    public function getTotalInput($startDate = null, $endDate = null)
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfMonth();

        return $this->history()
            ->whereBetween('created_at', [$start, $end])
            ->where('stock', '>', 0)
            ->sum('stock');
    }

    public function getTotalOutput($startDate = null, $endDate = null)
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfMonth();

        $historyOut = $this->history()
            ->whereBetween('created_at', [$start, $end])
            ->where('stock', '<', 0)
            ->sum('stock');

        $orderOut = $this->material_orders()
            ->whereBetween('created_at', [$start, $end])
            ->sum('quantity');

        return abs($historyOut) + $orderOut;
    }


    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM, YY h:mm:ss a');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('D, MMM, YY h:mm:ss a');
    }
}

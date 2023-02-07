<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use DB;

class Summary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'info_customer',
        'description',
        'payment',
        'payment_method_id',
        'customer_id',
        'branch_id',
        'type',
        'type_price',
    ];

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withTrashed();
    }

    public function getRecordTable(?string $type = 'order', ?int $branchId = 0)
    {
        return DB::table('summaries')->where('type', $type)->where('branch_id', $branchId)->where('user_id', Auth::id())->first();
    }
}

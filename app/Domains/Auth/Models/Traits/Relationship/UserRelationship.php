<?php

namespace App\Domains\Auth\Models\Traits\Relationship;

use App\Domains\Auth\Models\PasswordHistory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Order;
use App\Models\Logged;
use App\Models\Customer;
use App\Models\Departament;
use App\Models\AssignmentHistory;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * @return mixed
     */
    public function passwordHistories()
    {
        return $this->morphMany(PasswordHistory::class, 'model');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    public function loggeds(): HasMany
    {
        return $this->hasMany(Logged::class)->orderBy('created_at', 'desc');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function departaments(): HasMany
    {
        return $this->hasMany(Departament::class)->orderBy('created_at', 'desc');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AssignmentHistory::class)->orderBy('created_at', 'desc');
    }
}
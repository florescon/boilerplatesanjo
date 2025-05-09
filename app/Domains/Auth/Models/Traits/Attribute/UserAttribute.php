<?php

namespace App\Domains\Auth\Models\Traits\Attribute;

use Illuminate\Support\Facades\Hash;

/**
 * Trait UserAttribute.
 */
trait UserAttribute
{
    /**
     * @param $password
     */
    public function setPasswordAttribute($password): void
    {
        // If password was accidentally passed in already hashed, try not to double hash it
        // Note: Password Histories are logged from the \App\Domains\Auth\Observer\UserObserver class
        $this->attributes['password'] =
            (strlen($password) === 60 && preg_match('/^\$2y\$/', $password)) ||
            (strlen($password) === 95 && preg_match('/^\$argon2i\$/', $password)) ?
                $password :
                Hash::make($password);
    }

    /**
     * @return mixed
     */
    public function getAvatarAttribute()
    {
        return $this->getAvatar();
    }

    /**
     * @return string
     */
    public function getPermissionsLabelAttribute()
    {
        if ($this->hasAllAccess()) {
            return __('All');
        }

        if (! $this->permissions->count()) {
            return __('None');
        }

        return collect($this->getPermissionDescriptions())
            ->implode('<br/>');
    }

    /**
     * @return string
     */
    public function getRolesLabelAttribute()
    {
        if ($this->hasAllAccess()) {
            return __('All');
        }

        if (! $this->roles->count()) {
            return __('None');
        }

        return collect($this->getRoleNames())
            ->each(function ($role) {
                return ucwords($role);
            })
            ->implode('<br/>');
    }

    public function total_quantities()
    {
        return $this->assignments->sum('quantity');
    }


    public function getDetailsAttribute()
    {
        if($this->customer){
            $phone = $this->customer->phone ? $this->customer->phone.'<br>' : '';
            $address = $this->customer->address ? $this->customer->address.'<br>' : '';
            $rfc = $this->customer->rfc ? $this->customer->rfc.'<br>' : '';

            return $phone.' '.$address.' '.$rfc;
        }

        return '';
    }


    public function getRealNameAttribute()
    {
        return optional($this->customer)->short_name ?? $this->name;
    }

    /**
     * Return Total order price without shipping amount.
     */
    public function getTotalQuantitiesAttribute(): string
    {
        return $this->total_quantities();
    }

    public function price_making()
    {
        return $this->assignments->sum(function($parent) {
          return $parent->assignment->assignmentable->product->parent->price_making * $parent->quantity;
        });
    }

    public function getTotalQuantitiesWithMakingAttribute()
    {
        return $this->price_making();
    }


    /**
     * @return mixed
     */
    public function isRetail(): bool
    {
        return $this->customer->type_price === self::PRICE_RETAIL;
    }

    /**
     * @return mixed
     */
    public function isAverageWholesale(): bool
    {
        return $this->customer->type_price === self::PRICE_AVERAGE_WHOLESALE;
    }

    /**
     * @return mixed
     */
    public function isWholesale(): bool
    {
        return $this->customer->type_price === self::PRICE_WHOLESALE;
    }

    /**
     * @return mixed
     */
    public function isSpecial(): bool
    {
        return $this->customer->type_price === self::PRICE_SPECIAL;
    }

    /**
     * @param $type_price
     *
     * @return bool
     */
    public function isTypePrice($type_price): bool
    {
        return $this->type_price === $type_price;
    }

    public function getTypePriceLabelAttribute()
    {
        if($this->customer){
            if($this->isRetail()){
                return __('Retail price');
            }
            elseif($this->isAverageWholesale()){
                return __('Average wholesale price');
            }
            elseif($this->isWholesale()){
                return __('Wholesale price');
            }
            elseif($this->isSpecial()){
                return __('Special price');
            }
        }

        return __('Retail price');
    }

    public function getInitialsAttribute()
    {
        return collect(explode(' ', $this->name)) // separa las palabras
            ->filter() // elimina elementos vacíos por si acaso
            ->map(function ($word) {
                return strtoupper(substr($word, 0, 1)) . '.'; // toma la primera letra y agrega un punto
            })
            ->implode(''); // junta todo en un solo string, sin espacios
    }

}

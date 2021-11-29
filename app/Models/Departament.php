<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Departament extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'comment',
        'is_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function scopeDisabled(Builder $query): Builder
    {
        return $query->where('is_enabled', false);
    }

    /**
     * @return string
     */
    public function getIsEnabledDepartamentAttribute()
    {
        if ($this->is_enabled) {
            return "<span class='badge badge-success'>".__('Enabled').'</span>';
        }

        return "<span class='badge badge-danger'>".__('Disabled').'</span>';
    }

    /**
     * @return string
     */
    public function getIsDisabledAttribute()
    {
        if (!$this->is_enabled) {
            return "<span class='badge badge-danger'>".__('Disabled').'</span>';
        }

        return '';
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at->format('M, d Y');
    }

    public function getDateForHumansCreatedAttribute()
    {
        return $this->created_at->format('M, d Y');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'description', 
        'level', 
        'percentage', 
        'to_add_users'
    ];

    /**
     * Define if an to_add_users is enabled or not.
     *
     * @return bool
     */
    public function toAddUsers(): bool
    {
        return $this->to_add_users === true;
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getStatusAddUsersAttribute()
    {
        if($this->to_add_users){
            return "<span class='badge badge-primary'>".__('Yes').'</span>';
        }

        return "<span class='badge badge-secondary'>".__('No').'</span>';
    }

    public function getDateForHumansAttribute()
    {
        return $this->updated_at->format('M, d Y');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'to_add_users' => 'boolean',
    ];
}

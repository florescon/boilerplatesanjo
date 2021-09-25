<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Page extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ]
        ];
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * @return string
     */
    public function getActiveBackgroundAttribute()
    {
        if ($this->isActive()) {
            return 'background: rgb(238,174,202); background: linear-gradient(90deg, rgba(238,174,202,1) 0%, rgba(148,233,223,1) 100%);';
        }

        return 'background: rgb(253,29,29); background: linear-gradient(90deg, rgba(253,29,29,1) 33%, rgba(253,29,29,1) 39%, rgba(253,29,29,1) 42%, rgba(147,17,17,1) 71%, rgba(25,3,3,1) 95%, rgba(0,0,0,0.7707457983193278) 100%);';
    }

    /**
     * @return string
     */
    public function getIsActivePageAttribute()
    {
        if ($this->isActive()) {
            return __('Active').' '."<span class='badge badge-success'><i class='cil-check'></i></span>";
        }

        return "<span class='badge badge-danger'>".__('Inactive').'</span>';
    }

    /**
     * @return string
     */
    public function getInactivePageAttribute()
    {
        if (!$this->isActive()) {
            return "<span class='badge badge-danger'>".__('Inactive').'</span>';
        }
    }

    public function getDateDiffForHumansAttribute()
    {
        return $this->updated_at->diffForHumans();
    }
}

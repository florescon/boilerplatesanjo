<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Response;

class Document extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'file_emb',
        'file_dst',
        'comment',
        'is_enabled',
        'image',
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
                'source' => 'title',
                'onUpdate' => true,
            ]
        ];
    }

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

    public function getFileDstLabelAttribute()
    {
        if ($this->file_dst) {
            if (Storage::disk('public')->exists($this->file_dst)){
                return substr($this->file_dst, 10);
            }
        }

        return "<span class='badge badge-secondary'>".__('undefined').'</span>';
    }

    public function getFileEmbLabelAttribute()
    {
        if ($this->file_emb) {
            if (Storage::disk('public')->exists($this->file_emb)){
                return substr($this->file_emb, 10);
            }
        }

        return "<span class='badge badge-secondary'>".__('undefined').'</span>';
    }

    public function getDownloadDstAttribute()
    {
        if ($this->file_dst) {
            if (Storage::disk('public')->exists($this->file_dst)){
                return "<a  href=".route('admin.document.download_dst', $this->id)." class='btn btn-primary btn-sm'>".__('Download DST').'</a>';
            }
        }

        return "<span class='badge badge-secondary'>".__('undefined').'</span>';
    }

    public function getDownloadEmbAttribute()
    {
        if ($this->file_emb) {
            if (Storage::disk('public')->exists($this->file_dst)){
                return "<a  href=".route('admin.document.download_emb', $this->id)." class='btn btn-primary btn-sm'>".__('Download EMB').'</a>';
            }
        }

        return "<span class='badge badge-secondary'>".__('undefined').'</span>';
    }

    /**
     * @return string
     */
    public function getIsEnabledDocumentAttribute()
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
        return $this->updated_at->isoFormat('D, MMM, YY');
    }

    public function getDateForHumansCreatedAttribute()
    {
        return $this->created_at->isoFormat('D, MMM, YY');
    }
}

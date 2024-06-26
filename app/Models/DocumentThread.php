<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentThread extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_id', 
        'thread_id',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class)->withTrashed();
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class)->withTrashed();
    }

}

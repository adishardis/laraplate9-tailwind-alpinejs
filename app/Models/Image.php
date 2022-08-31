<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use MainModel;
    use SoftDeletes;

    protected $dateFormat = 'U';
    protected $fillable = [
        'type',
        'size',
        'mime_type',
        'file_name',
        'path',
        'height',
        'width',
        'parent_id',
        'attachable_type',
        'attachable_id',
    ];
    // protected $appends =['url'];

    /**
     * Get all of the owning attachable models.
     */
    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     * Relation to parent.
     */
    public function parent()
    {
        return $this->belongsTo(Image::class);
    }

    /**
     * Get all children
     */
    public function childrens()
    {
        return $this->hasMany(Image::class, 'parent_id');
    }

    /**
     * Get Url
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }

    /**
     * Get Full Url
     */
    public function getFullurlAttribute()
    {
        return asset($this->url);
    }
}

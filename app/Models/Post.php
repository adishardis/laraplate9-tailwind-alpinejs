<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Traits\NotificationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use MainModel;
    use HasFactory;
    use SoftDeletes;
    use NotificationTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'is_edited',
        'user_id',
    ];

    protected $dateFormat = 'U';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'status' => PostStatus::class,
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = $model->user_id ?? (auth()->id() ?? null);
            $model->slug = Str::slug($model->title.' '.time());
        });
        static::updating(function ($model) {
            $model->is_edited = 1;
        });
    }

    /**
     * Relation to author
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all likes / dislikes
     */
    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * Get summary
     */
    public function summary()
    {
        return $this->hasOne(PostSummary::class);
    }

    /**
     * Get like by user
     */
    public function userLike($userId)
    {
        return $this->likes->where('user_id', $userId)->first();
    }
}

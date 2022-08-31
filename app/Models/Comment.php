<?php

namespace App\Models;

use App\Traits\NotificationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
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
        'post_id',
        'user_id',
        'parent_id',
        'comment',
        'is_edited',
    ];
    protected $dateFormat = 'U';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updating(function ($model) {
            $model->is_edited = 1;
        });
    }

    /**
     * Interact with the comment's author.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function userId(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ?? (auth()->id() ?? null),
        );
    }

    /**
     * Relation to post
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relation to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation to parent
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Get all children
     */
    public function childrens()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Get all likes / dislikes
     */
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Get summary
     */
    public function summary()
    {
        return $this->hasOne(CommentSummary::class);
    }

    /**
     * Get like by user
     */
    public function userLike($userId)
    {
        return $this->likes->where('user_id', $userId)->first();
    }
}

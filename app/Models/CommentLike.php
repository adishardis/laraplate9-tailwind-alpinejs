<?php

namespace App\Models;

use App\Traits\NotificationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentLike extends Model
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
        'comment_id',
        'user_id',
        'is_like',
        'is_dislike',
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
     * Interact with the like's author.
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
     * Relation to comment
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Relation to author
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

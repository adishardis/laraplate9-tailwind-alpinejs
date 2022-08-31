<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Shanmuga\LaravelEntrust\Traits\LaravelEntrustUserTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use MainModel;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use LaravelEntrustUserTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'email_verified_at',
    ];

    protected $dateFormat = 'U';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's roles.
     *
     * @return array
     */
    public function getRoleArrayAttribute(): array
    {
        $arr = [];
        if (!$this->roles) {
            return $arr;
        }
        foreach ($this->roles as $role) {
            $arr[$role->id] = $role->name;
        }
        return $arr;
    }

    /**
     * Get social account
     */
    public function socialAccount()
    {
        return $this->hasOne(UserSocialAccount::class);
    }

    /**
     * Get user setting
     */
    public function setting()
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * Get all post
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all post likes
     */
    public function post_likes()
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * Get all comment likes
     */
    public function comment_likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Avatar image
     */
    public function avatar()
    {
        return $this->morphOne(Image::class, 'attachable')->whereType('avatar');
    }

    /**
     * Background image
     */
    public function background()
    {
        return $this->morphOne(Image::class, 'attachable')->whereType('background');
    }
}

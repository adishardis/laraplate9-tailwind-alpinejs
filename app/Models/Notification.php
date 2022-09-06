<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'notifiable_type',
        'notifiable_id',
        'subject',
        'message',
        'type',
        'read_at',
    ];

    protected $dateFormat = 'U';

    protected $dates = [
        'read_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Scope a query to unread notification.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUserId($query, $userId = null)
    {
        if (! $userId) {
            $userId = auth()->id();
        }

        return $query->whereUserId($userId);
    }

    /**
     * Scope a query to unread notification.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}

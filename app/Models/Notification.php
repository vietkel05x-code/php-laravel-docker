<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title', 'body', 'created_by',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notifications')
            ->withPivot('read_at');
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    // Scope for unread notifications
    public function scopeUnreadForUser($query, $userId)
    {
        return $query->whereHas('userNotifications', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereNull('read_at');
        });
    }
}

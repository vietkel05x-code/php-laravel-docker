<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $table = 'user_notifications';

    protected $fillable = [
        'notification_id', 'user_id', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public $timestamps = false;

    // Relationships
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        if (!$this->read_at) {
            static::where('user_id', $this->user_id)
                ->where('notification_id', $this->notification_id)
                ->update(['read_at' => now()]);
        }
    }
}

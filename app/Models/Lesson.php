<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'section_id', 'title', 'video_path', 'hls_path', 'video_url', 'attachment_path',
        'duration', 'is_preview', 'position', 'cloudinary_id',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
    ];

    // Relationships
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function userProgress($userId)
    {
        return $this->progress()->where('user_id', $userId)->first();
    }

    public function isCompletedBy($userId)
    {
        return $this->progress()
            ->where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->exists();
    }
}

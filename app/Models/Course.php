<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'instructor_id', 'category_id', 'title', 'slug', 'short_description',
        'description', 'description_html', 'price', 'compare_at_price',
        'thumbnail_path', 'status', 'total_duration', 'level', 'language',
        'enrolled_students', 'rating', 'rating_count', 'video_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'rating' => 'decimal:1',
    ];

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'course_category');
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('position');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper methods
    public function isEnrolledBy($userId)
    {
        return $this->enrollments()
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->exists();
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('img/courses/' . $this->thumbnail_path);
        }
        return asset('img/courses/default.jpg');
    }

    /**
     * Format total_duration for display
     * Returns formatted string: "X giây", "X phút Y giây", or "X giờ Y phút Z giây"
     */
    public function getFormattedTotalDurationAttribute()
    {
        $seconds = (int) ($this->total_duration ?? 0);
        
        if ($seconds < 60) {
            // Less than 1 minute: show seconds only
            return $seconds . ' giây';
        } elseif ($seconds < 3600) {
            // Less than 60 minutes: show minutes and seconds
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            
            if ($remainingSeconds > 0) {
                return $minutes . ' phút ' . $remainingSeconds . ' giây';
            } else {
                return $minutes . ' phút';
            }
        } else {
            // 60 minutes or more: show hours, minutes
            $hours = floor($seconds / 3600);
            $remainingSeconds = $seconds % 3600;
            $minutes = floor($remainingSeconds / 60);
            // $secs = $remainingSeconds % 60;
            
            $parts = [];
            $parts[] = $hours . ' giờ';
            
            if ($minutes > 0) {
                $parts[] = $minutes . ' phút';
            }
            
            // if ($secs > 0) {
            //     $parts[] = $secs . ' giây';
            // }
            
            return implode(' ', $parts);
        }
    }

    /**
     * Update total_duration by summing all lesson durations in this course
     */
    public function updateTotalDuration()
    {
        $totalDuration = 0;
        
        // Get all lessons through sections
        foreach ($this->sections as $section) {
            foreach ($section->lessons as $lesson) {
                $totalDuration += (int) ($lesson->duration ?? 0);
            }
        }
        
        $this->total_duration = $totalDuration;
        $this->save();
        
        return $totalDuration;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function media()
    {
        return $this->hasMany(CategoryMedia::class);
    }

    // Nếu bạn muốn lấy nhanh thumbnail chính:
    public function thumbnail()
    {
        return $this->hasOne(CategoryMedia::class)->where('type', 'thumbnail');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}

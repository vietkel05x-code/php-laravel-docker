<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Course;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    public function index(Request $request)
    {
        $query = Review::with(['user', 'course']);

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('course', function($courseQuery) use ($search) {
                      $courseQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();
        $courses = Course::all();

        return view('admin.reviews.index', compact('reviews', 'courses'));
    }

    public function destroy(Review $review)
    {
        $course = $review->course;
        
        $review->delete();

        // Recalculate course rating
        $avgRating = Review::where('course_id', $course->id)->avg('rating');
        $ratingCount = Review::where('course_id', $course->id)->count();

        $course->update([
            'rating' => $avgRating ? round($avgRating, 1) : 0,
            'rating_count' => $ratingCount,
        ]);

        return back()->with('success', 'Đã xóa đánh giá!');
    }

    public function hide(Review $review)
    {
        // We can add a 'hidden' column to reviews table if needed
        // For now, we'll just delete it
        return $this->destroy($review);
    }
}


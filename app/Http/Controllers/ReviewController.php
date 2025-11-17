<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{

    public function store(Request $request, Course $course)
    {
        // Check if user is enrolled
        if (!$course->isEnrolledBy(Auth::id())) {
            return back()->with('error', 'Bạn cần đăng ký khóa học để đánh giá!');
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($course, $validated, $existingReview) {
            if ($existingReview) {
                // Update existing review
                $existingReview->update($validated);
                $review = $existingReview;
            } else {
                // Create new review
                $review = Review::create([
                    'user_id' => Auth::id(),
                    'course_id' => $course->id,
                    'rating' => $validated['rating'],
                    'content' => $validated['content'] ?? null,
                ]);
            }

            // Recalculate course rating
            $avgRating = Review::where('course_id', $course->id)->avg('rating');
            $ratingCount = Review::where('course_id', $course->id)->count();

            $course->update([
                'rating' => round($avgRating, 1),
                'rating_count' => $ratingCount,
            ]);
        });

        return back()->with('success', $existingReview ? 'Đã cập nhật đánh giá!' : 'Cảm ơn bạn đã đánh giá!');
    }

    public function destroy(Review $review)
    {
        // Only allow user to delete their own review or admin
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $course = $review->course;

        DB::transaction(function () use ($review, $course) {
            $review->delete();

            // Recalculate course rating
            $avgRating = Review::where('course_id', $course->id)->avg('rating');
            $ratingCount = Review::where('course_id', $course->id)->count();

            $course->update([
                'rating' => $avgRating ? round($avgRating, 1) : 0,
                'rating_count' => $ratingCount,
            ]);
        });

        return back()->with('success', 'Đã xóa đánh giá!');
    }
}


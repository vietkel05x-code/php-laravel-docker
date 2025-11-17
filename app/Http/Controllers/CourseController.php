<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::where('status', 'published')
            ->with(['instructor', 'category']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by instructor
        if ($request->filled('instructor')) {
            $query->where('instructor_id', $request->instructor);
        }

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        // Handle price_range radio button
        if ($request->filled('price_range')) {
            $priceRange = $request->price_range;
            if ($priceRange === 'free') {
                $query->where('price', 0);
            } elseif ($priceRange === '0-500000') {
                $query->whereBetween('price', [0, 500000]);
            } elseif ($priceRange === '500000-1000000') {
                $query->whereBetween('price', [500000, 1000000]);
            } elseif ($priceRange === '1000000') {
                $query->where('price', '>=', 1000000);
            }
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'students':
                $query->orderBy('enrolled_students', 'desc');
                break;
            default:
                $query->latest();
        }

        $courses = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $instructors = User::whereHas('courses')->distinct()->get();
        
        return view('courses.index', compact('courses', 'categories', 'instructors'));
    }

    public function show(string $slug)
    {
        $course = Course::with(['instructor', 'category', 'sections.lessons', 'reviews.user'])
            ->where('slug', $slug)
            ->firstOrFail();
        
        $isEnrolled = Auth::check() && $course->isEnrolledBy(Auth::id());
        
        // Get user's review if exists
        $userReview = null;
        if (Auth::check()) {
            $userReview = $course->reviews()->where('user_id', Auth::id())->first();
        }
        
        return view('courses.show', compact('course', 'isEnrolled', 'userReview'));
    }
}

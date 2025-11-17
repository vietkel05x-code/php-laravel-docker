<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Order;
use App\Models\Enrollment;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_courses' => Course::count(),
            'published_courses' => Course::where('status', 'published')->count(),
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'paid')->sum('total'),
            'total_enrollments' => Enrollment::where('status', 'active')->count(),
            'total_reviews' => Review::count(),
        ];

        // Recent orders
        $recentOrders = Order::with(['user', 'items.course'])
            ->latest()
            ->take(5)
            ->get();

        // Top courses
        $topCourses = Course::orderBy('enrolled_students', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard-new', compact('stats', 'recentOrders', 'topCourses'));
    }
}


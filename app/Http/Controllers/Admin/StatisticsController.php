<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Review;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    // R6.1: Revenue Statistics
    public function revenue(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Default date range
        if (!$startDate || !$endDate) {
            $endDate = now();
            switch ($period) {
                case 'day':
                    $startDate = now()->subDays(7);
                    break;
                case 'week':
                    $startDate = now()->subWeeks(4);
                    break;
                case 'month':
                    $startDate = now()->subMonths(6);
                    break;
                case 'year':
                    $startDate = now()->subYears(2);
                    break;
            }
        } else {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        }

        // Total revenue
        $totalRevenue = Order::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        // Total orders
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $paidOrders = Order::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Revenue by payment method
        $revenueByPayment = Payment::where('status', 'succeeded')
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select('provider', DB::raw('SUM(amount) as total'))
            ->groupBy('provider')
            ->get();

        // Revenue chart data
        $chartData = $this->getRevenueChartData($startDate, $endDate, $period);

        // Recent orders
        $recentOrders = Order::with(['user', 'items.course', 'payment'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.statistics.revenue', compact(
            'totalRevenue', 'totalOrders', 'paidOrders', 'revenueByPayment',
            'chartData', 'recentOrders', 'period', 'startDate', 'endDate'
        ));
    }

    // R6.2: Course Statistics
    public function courses()
    {
        // Total courses
        $totalCourses = Course::count();
        $publishedCourses = Course::where('status', 'published')->count();
        $draftCourses = Course::where('status', 'draft')->count();

        // Enrollment statistics
        $totalEnrollments = Enrollment::where('status', 'active')->count();
        $avgEnrollmentsPerCourse = $totalCourses > 0 ? round($totalEnrollments / $totalCourses, 2) : 0;

        // Top courses by enrollment
        $topCoursesByEnrollment = Course::with('category', 'instructor')
            ->orderBy('enrolled_students', 'desc')
            ->take(10)
            ->get();

        // Top courses by rating
        $topCoursesByRating = Course::with('category', 'instructor')
            ->where('rating', '>', 0)
            ->orderBy('rating', 'desc')
            ->orderBy('rating_count', 'desc')
            ->take(10)
            ->get();

        // Courses by category
        $coursesByCategory = Course::select('category_id', DB::raw('COUNT(*) as count'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        // Completion rate (courses with progress data)
        $coursesWithProgress = Course::has('enrollments')->get();
        $completionStats = [];
        foreach ($coursesWithProgress as $course) {
            $enrollments = $course->enrollments()->where('status', 'active')->count();
            if ($enrollments > 0) {
                $completedLessons = LessonProgress::whereHas('lesson', function($q) use ($course) {
                    $q->whereHas('section', function($q2) use ($course) {
                        $q2->where('course_id', $course->id);
                    });
                })->where(function($q) {
                    $q->where('is_completed', true)->orWhereNotNull('completed_at');
                })->count();
                
                $totalLessons = $course->sections()->withCount('lessons')->get()->sum('lessons_count');
                $completionRate = $totalLessons > 0 ? round(($completedLessons / ($totalLessons * $enrollments)) * 100, 2) : 0;
                
                $completionStats[] = [
                    'course' => $course,
                    'enrollments' => $enrollments,
                    'completion_rate' => $completionRate,
                ];
            }
        }

        return view('admin.statistics.courses', compact(
            'totalCourses', 'publishedCourses', 'draftCourses',
            'totalEnrollments', 'avgEnrollmentsPerCourse',
            'topCoursesByEnrollment', 'topCoursesByRating',
            'coursesByCategory', 'completionStats'
        ));
    }

    // R6.3: Student Statistics
    public function students()
    {
        // Total students
        $totalStudents = User::where('role', 'student')->count();
        $activeStudents = User::where('role', 'student')
            ->whereHas('enrollments', function($q) {
                $q->where('status', 'active');
            })
            ->count();

        // New students (last 30 days)
        $newStudents = User::where('role', 'student')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // Students with enrollments
        $studentsWithEnrollments = User::where('role', 'student')
            ->has('enrollments')
            ->count();

        // Enrollment rate
        $enrollmentRate = $totalStudents > 0 ? round(($studentsWithEnrollments / $totalStudents) * 100, 2) : 0;

        // Completion statistics
        $studentsWithProgress = User::where('role', 'student')
            ->has('lessonProgress')
            ->count();

        $completionData = [];
        $students = User::where('role', 'student')->has('enrollments')->take(100)->get();
        foreach ($students as $student) {
            $enrollments = $student->enrollments()->where('status', 'active')->count();
            $completedLessons = $student->lessonProgress()->where(function($q) {
                $q->where('is_completed', true)->orWhereNotNull('completed_at');
            })->count();
            $totalLessons = LessonProgress::whereIn('user_id', [$student->id])
                ->distinct('lesson_id')
                ->count();
            
            $completionRate = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0;
            
            $completionData[] = [
                'student' => $student,
                'enrollments' => $enrollments,
                'completed_lessons' => $completedLessons,
                'total_lessons' => $totalLessons,
                'completion_rate' => $completionRate,
            ];
        }

        // Sort by completion rate
        usort($completionData, function($a, $b) {
            return $b['completion_rate'] <=> $a['completion_rate'];
        });
        $completionData = array_slice($completionData, 0, 20); // Top 20

        // Students by enrollment count
        $studentsByEnrollment = User::where('role', 'student')
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.statistics.students', compact(
            'totalStudents', 'activeStudents', 'newStudents',
            'studentsWithEnrollments', 'enrollmentRate',
            'studentsWithProgress', 'completionData', 'studentsByEnrollment'
        ));
    }

    private function getRevenueChartData($startDate, $endDate, $period)
    {
        $data = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $label = '';
            $next = null;

            switch ($period) {
                case 'day':
                    $label = $current->format('d/m');
                    $next = $current->copy()->addDay();
                    break;
                case 'week':
                    $label = 'Tuần ' . $current->format('W/Y');
                    $next = $current->copy()->addWeek();
                    break;
                case 'month':
                    $label = $current->format('m/Y');
                    $next = $current->copy()->addMonth();
                    break;
                case 'year':
                    $label = $current->format('Y');
                    $next = $current->copy()->addYear();
                    break;
            }

            $revenue = Order::where('status', 'paid')
                ->whereBetween('created_at', [$current, $next ? $next->copy()->subSecond() : $endDate])
                ->sum('total');

            $data[] = [
                'label' => $label,
                'revenue' => (float) $revenue,
            ];

            $current = $next ?: $current->addDay();
        }

        return $data;
    }
}


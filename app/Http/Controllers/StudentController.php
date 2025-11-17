<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function myCourses()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with(['course.category', 'course.instructor', 'course.sections.lessons'])
            ->orderBy('enrolled_at', 'desc')
            ->get();

        return view('student.my-courses', compact('enrollments'));
    }

    public function learn(Course $course)
    {
        // Check enrollment
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Bạn chưa đăng ký khóa học này!');
        }

        // Eager load sections and lessons with ordering
        $course->load([
            'sections' => function($query) {
                $query->orderBy('position');
            },
            'sections.lessons' => function($query) {
                $query->orderBy('position')->select('id', 'section_id', 'title', 'is_preview', 'position', 'duration');
            }
        ]);
        
        // Get all lesson IDs in one query
        $lessonIds = DB::table('lessons')
            ->join('sections', 'lessons.section_id', '=', 'sections.id')
            ->where('sections.course_id', $course->id)
            ->pluck('lessons.id')
            ->toArray();
        
        // Get progress for all lessons in one query
        $progress = LessonProgress::where('user_id', Auth::id())
            ->whereIn('lesson_id', $lessonIds)
            ->pluck('completed_at', 'lesson_id')
            ->toArray();

        // Determine which lessons are locked (optimized)
        $lockedLessons = [];
        $allLessons = collect();
        
        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                $allLessons->push($lesson);
            }
        }
        
        // Sort by position across sections
        $allLessons = $allLessons->sortBy(function($lesson) {
            return $lesson->section->position * 1000 + $lesson->position;
        })->values();
        
        foreach ($allLessons as $index => $lesson) {
            if ($lesson->is_preview) {
                continue; // Preview lessons are never locked
            }
            
            if ($index > 0) {
                $previousLesson = $allLessons[$index - 1];
                if (!isset($progress[$previousLesson->id])) {
                    $lockedLessons[$lesson->id] = true;
                }
            }
        }

        return view('student.learn', compact('course', 'enrollment', 'progress', 'lockedLessons'));
    }

    public function lesson(Course $course, Lesson $lesson)
    {
        // Check enrollment
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Bạn chưa đăng ký khóa học này!');
        }

        // Eager load section to avoid N+1
        $lesson->load('section');
        
        // Check if lesson belongs to course
        if ($lesson->section->course_id !== $course->id) {
            abort(404);
        }

        // Eager load sections and lessons with ordering and only needed fields
        $course->load([
            'sections' => function($query) {
                $query->orderBy('position')->select('id', 'course_id', 'title', 'position');
            },
            'sections.lessons' => function($query) {
                $query->orderBy('position')->select('id', 'section_id', 'title', 'is_preview', 'position', 'duration', 'video_path', 'video_url', 'attachment_path');
            }
        ]);
        
        // Get all lesson IDs in one query
        $lessonIds = DB::table('lessons')
            ->join('sections', 'lessons.section_id', '=', 'sections.id')
            ->where('sections.course_id', $course->id)
            ->pluck('lessons.id')
            ->toArray();
        
        // Get progress for all lessons in one query
        $progress = LessonProgress::where('user_id', Auth::id())
            ->whereIn('lesson_id', $lessonIds)
            ->pluck('completed_at', 'lesson_id')
            ->toArray();

        // Get all lessons in order (sorted by section position and lesson position)
        $allLessons = collect();
        foreach ($course->sections as $section) {
            foreach ($section->lessons as $l) {
                $allLessons->push($l);
            }
        }
        
        // Sort by position across sections
        $allLessons = $allLessons->sortBy(function($l) {
            return $l->section->position * 1000 + $l->position;
        })->values();
        
        $currentIndex = $allLessons->search(function ($l) use ($lesson) {
            return $l->id === $lesson->id;
        });

        $previousLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        // Determine locked lessons (for sidebar display)
        $lockedLessons = [];
        foreach ($allLessons as $index => $l) {
            if ($l->is_preview) {
                continue;
            }
            if ($index > 0) {
                $prevLesson = $allLessons[$index - 1];
                if (!isset($progress[$prevLesson->id])) {
                    $lockedLessons[$l->id] = true;
                }
            }
        }

        // Check if current lesson is locked
        $isLocked = false;
        $lockedReason = null;
        
        if (!$lesson->is_preview && $previousLesson) {
            if (!isset($progress[$previousLesson->id])) {
                $isLocked = true;
                $lockedReason = "Bạn cần hoàn thành bài học trước: \"{$previousLesson->title}\"";
            }
        }

        return view('student.lesson', compact('course', 'lesson', 'enrollment', 'progress', 'previousLesson', 'nextLesson', 'isLocked', 'lockedReason', 'lockedLessons'));
    }

    public function completeLesson(Lesson $lesson)
    {
        $userId = Auth::id();

        // Check if user is enrolled in the course
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('course_id', $lesson->section->course_id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Bạn chưa đăng ký khóa học này!'], 403);
        }

        // Create or update progress
        LessonProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $lesson->id,
            ],
            [
                'completed_at' => now(),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Đã đánh dấu hoàn thành!']);
    }

    /**
     * API endpoint to get sidebar content for a course
     * Used to update sidebar after lesson completion without full page reload
     */
    public function getSidebar(Course $course, Request $request)
    {
        $currentLessonId = $request->get('lesson');
        // Check enrollment
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Bạn chưa đăng ký khóa học này!'], 403);
        }

        // Eager load sections and lessons
        $course->load([
            'sections' => function($query) {
                $query->orderBy('position')->select('id', 'course_id', 'title', 'position');
            },
            'sections.lessons' => function($query) {
                $query->orderBy('position')->select('id', 'section_id', 'title', 'is_preview', 'position', 'duration');
            }
        ]);
        
        // Get all lesson IDs
        $lessonIds = DB::table('lessons')
            ->join('sections', 'lessons.section_id', '=', 'sections.id')
            ->where('sections.course_id', $course->id)
            ->pluck('lessons.id')
            ->toArray();
        
        // Get progress
        $progress = LessonProgress::where('user_id', Auth::id())
            ->whereIn('lesson_id', $lessonIds)
            ->pluck('completed_at', 'lesson_id')
            ->toArray();

        // Determine locked lessons
        $lockedLessons = [];
        $allLessons = collect();
        
        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                $allLessons->push($lesson);
            }
        }
        
        $allLessons = $allLessons->sortBy(function($lesson) {
            return $lesson->section->position * 1000 + $lesson->position;
        })->values();
        
        foreach ($allLessons as $index => $lesson) {
            if ($lesson->is_preview) {
                continue;
            }
            
            if ($index > 0) {
                $previousLesson = $allLessons[$index - 1];
                if (!isset($progress[$previousLesson->id])) {
                    $lockedLessons[$lesson->id] = true;
                }
            }
        }

        // Calculate progress
        $totalLessons = $course->sections->sum(function($s) {
            return $s->lessons->count();
        });
        $completedLessons = count($progress);
        $courseProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        // Render sidebar view
        $html = view('student.partials.sidebar', compact('course', 'progress', 'lockedLessons', 'courseProgress', 'totalLessons', 'completedLessons', 'currentLessonId'))->render();

        return response()->json(['html' => $html]);
    }
}


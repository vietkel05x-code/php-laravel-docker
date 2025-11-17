<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('courses:populate-empty-content', function () {
    $emptyCourses = Course::doesntHave('sections')->get();
    if ($emptyCourses->isEmpty()) {
        $this->info('Không có khóa học nào trống (chưa có section).');
        return 0;
    }

    $totalSections = 0;
    $totalLessons = 0;

    foreach ($emptyCourses as $course) {
        $this->line("→ Khóa học: [{$course->id}] {$course->title}");
        // Tạo 2 phần
        for ($s = 1; $s <= 2; $s++) {
            $section = Section::create([
                'course_id' => $course->id,
                'title' => $s === 1 ? 'Phần 1: Giới thiệu' : 'Phần 2: Nội dung chính',
                'position' => $s,
            ]);
            $totalSections++;

            // Tạo 2 bài học cho mỗi phần
            for ($l = 1; $l <= 2; $l++) {
                Lesson::create([
                    'section_id' => $section->id,
                    'title' => $l === 1 ? 'Bài học 1' : 'Bài học 2',
                    'position' => $l,
                    'is_preview' => $s === 1 && $l === 1, // Cho phép preview bài đầu tiên của khóa học
                ]);
                $totalLessons++;
            }
        }
    }

    $this->info("Đã thêm {$totalSections} phần và {$totalLessons} bài học cho {$emptyCourses->count()} khóa học trống.");
    return 0;
})->purpose('Thêm 2 phần và 2 bài học (tên mặc định) cho các khóa học chưa có nội dung.');

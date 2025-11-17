@extends('layouts.app')

@section('title', 'Khóa học của tôi')

@section('content')

@php
  $breadcrumbItems = [
    ['label' => 'Trang chủ', 'url' => route('home')],
    ['label' => 'Khóa học của tôi']
  ];
@endphp
@include('components.breadcrumb', ['items' => $breadcrumbItems])

<section class="page-wrapper" style="max-width:1200px;margin:40px auto;padding:0 20px">
  <h1 style="margin-bottom:24px">Khóa học của tôi</h1>

  @if($enrollments->count() > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));gap:24px">
      @foreach($enrollments as $enrollment)
        @php
          $course = $enrollment->course;
          // Calculate progress
          $totalLessons = $course->sections->sum(function($section) {
            return $section->lessons->count();
          });
          $completedLessons = \App\Models\LessonProgress::where('user_id', Auth::id())
            ->whereIn('lesson_id', $course->sections->flatMap->lessons->pluck('id'))
            ->whereNotNull('completed_at')
            ->count();
          $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
        @endphp

        <div style="border:1px solid #eee;border-radius:12px;overflow:hidden;background:#fff">
          <a href="{{ route('student.learn', $course->slug) }}">
            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" 
                 style="width:100%;height:180px;object-fit:cover">
          </a>
          
          <div style="padding:16px">
            <h3 style="margin:0 0 8px 0;font-size:16px">
              <a href="{{ route('student.learn', $course->slug) }}" style="color:#333;text-decoration:none">
                {{ $course->title }}
              </a>
            </h3>
            <p style="color:#666;margin:0 0 12px 0;font-size:14px">{{ $course->instructor->name ?? 'Instructor' }}</p>
            
            @if($totalLessons > 0)
              <div style="margin-bottom:12px">
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:12px;color:#666">
                  <span>Tiến độ</span>
                  <span>{{ round($progress) }}%</span>
                </div>
                <div style="width:100%;height:6px;background:#eee;border-radius:3px;overflow:hidden">
                  <div style="width:{{ $progress }}%;height:100%;background:#a435f0;transition:width 0.3s"></div>
                </div>
              </div>
            @endif

            <a href="{{ route('student.learn', $course->slug) }}" 
               style="display:block;text-align:center;padding:10px;background:#a435f0;color:white;border-radius:8px;text-decoration:none;font-weight:bold">
              {{ $progress > 0 ? 'Tiếp tục học' : 'Bắt đầu học' }}
            </a>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div style="text-align:center;padding:60px 20px">
      <p style="font-size:18px;color:#666;margin-bottom:24px">Bạn chưa đăng ký khóa học nào</p>
      <a href="{{ route('home') }}" style="display:inline-block;padding:12px 24px;background:#a435f0;color:white;border-radius:8px;text-decoration:none">
        Khám phá khóa học
      </a>
    </div>
  @endif
</section>
@endsection


@extends('layouts.app')

@section('title', $course->title)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/student-learn.css') }}">
@endpush

@section('content')
<section class="learn-page">
  <div class="learn-grid">
    <!-- Sidebar: Course Content -->
    <aside class="learn-sidebar">
      @php
        // Calculate course progress
        $totalLessons = $course->sections->sum(function($s) {
          return $s->lessons->count();
        });
        $completedLessons = count($progress);
        $courseProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
      @endphp
      
      <h2 class="learn-sidebar__title">Nội dung khóa học</h2>
      
      <!-- Course Progress -->
      <div class="learn-sidebar__progress">
        <div class="learn-sidebar__progress-header">
          <span class="learn-sidebar__progress-label">Tiến độ khóa học</span>
          <span class="learn-sidebar__progress-percent">{{ $courseProgress }}%</span>
        </div>
        <div class="learn-sidebar__progress-bar">
          <div class="learn-sidebar__progress-fill" style="width: {{ $courseProgress }}%"></div>
        </div>
        <div class="learn-sidebar__progress-text">
          {{ $completedLessons }} / {{ $totalLessons }} bài học đã hoàn thành
        </div>
      </div>
      
      <div class="learn-sidebar__sections">
        @foreach($course->sections as $section)
          <div class="learn-sidebar__section" data-section-id="{{ $section->id }}">
            <div class="learn-sidebar__section-header">
              <h3 class="learn-sidebar__section-title">{{ $section->title }}</h3>
              <button class="learn-sidebar__section-toggle" type="button" aria-label="Thu gọn/mở rộng">
                <i class="fas fa-chevron-down"></i>
              </button>
            </div>
            <div class="learn-sidebar__lessons">
              @foreach($section->lessons as $lesson)
                @php
                  $isLocked = isset($lockedLessons[$lesson->id]);
                  $isCompleted = isset($progress[$lesson->id]);
                @endphp
                @if($isLocked)
                  <div class="lesson-item lesson-item--locked">
                    <span class="lesson-item__icon lesson-item__icon--normal">🔒</span>
                    <span class="lesson-item__title">{{ $lesson->title }}</span>
                    <span style="font-size:11px;color:#999">Khóa</span>
                  </div>
                @else
                  <a href="{{ route('student.lesson', ['course' => $course->slug, 'lesson' => $lesson->id]) }}" 
                     class="lesson-item {{ $isCompleted ? 'lesson-item--completed' : 'lesson-item--normal' }}">
                    <span class="lesson-item__icon {{ $isCompleted ? 'lesson-item__icon--completed' : 'lesson-item__icon--normal' }}">
                      @if($isCompleted)
                        <span>✓</span>
                      @else
                        <span>{{ $loop->iteration }}</span>
                      @endif
                    </span>
                    <span class="lesson-item__title">{{ $lesson->title }}</span>
                    @if($lesson->is_preview)
                      <span class="lesson-item__badge lesson-item__badge--preview">Preview</span>
                    @endif
                  </a>
                @endif
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </aside>

    <!-- Main Content -->
    <main class="learn-main">
      <div class="learn-welcome card">
        <h1 class="learn-welcome__title">{{ $course->title }}</h1>
        <p class="learn-welcome__description">{{ $course->short_description }}</p>
        
        @if($course->sections->count() > 0)
          <div class="learn-welcome__message">
            <p>Chọn một bài học từ menu bên trái để bắt đầu học</p>
          </div>
        @else
          <div class="learn-welcome__message learn-welcome__message--warning">
            <p>Khóa học này chưa có nội dung. Vui lòng quay lại sau.</p>
          </div>
        @endif
      </div>

      <!-- Course Info -->
      <div class="learn-info card">
        <h2 class="learn-info__title">Về khóa học</h2>
        <div class="learn-info__grid">
          <div>
            <p class="learn-info__item-label">Giảng viên</p>
            <p class="learn-info__item-value">{{ $course->instructor->name ?? 'N/A' }}</p>
          </div>
          <div>
            <p class="learn-info__item-label">Cấp độ</p>
            <p class="learn-info__item-value">{{ ucfirst($course->level ?? 'Beginner') }}</p>
          </div>
          <div>
            <p class="learn-info__item-label">Ngôn ngữ</p>
            <p class="learn-info__item-value">{{ $course->language ?? 'Vietnamese' }}</p>
          </div>
          <div>
            <p class="learn-info__item-label">Thời lượng</p>
            <p class="learn-info__item-value">{{ $course->formatted_total_duration }}</p>
          </div>
        </div>

        @if($course->description_html)
          <div class="learn-info__description">
            {!! $course->description_html !!}
          </div>
        @endif
      </div>
    </main>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Section collapse/expand functionality
  document.querySelectorAll('.learn-sidebar__section-toggle').forEach(toggle => {
    toggle.addEventListener('click', function() {
      const section = this.closest('.learn-sidebar__section');
      const lessons = section.querySelector('.learn-sidebar__lessons');
      const icon = this.querySelector('i');
      
      if (lessons) {
        const isExpanded = !lessons.classList.contains('learn-sidebar__lessons--collapsed');
        
        if (isExpanded) {
          lessons.classList.add('learn-sidebar__lessons--collapsed');
          icon.classList.remove('fa-chevron-down');
          icon.classList.add('fa-chevron-right');
        } else {
          lessons.classList.remove('learn-sidebar__lessons--collapsed');
          icon.classList.remove('fa-chevron-right');
          icon.classList.add('fa-chevron-down');
        }
      }
    });
  });
});
</script>
@endsection

<h2 class="lesson-sidebar__title">Nội dung khóa học</h2>

<!-- Course Progress -->
<div class="lesson-sidebar__progress">
  <div class="lesson-sidebar__progress-header">
    <span class="lesson-sidebar__progress-label">Tiến độ khóa học</span>
    <span class="lesson-sidebar__progress-percent">{{ $courseProgress }}%</span>
  </div>
  <div class="lesson-sidebar__progress-bar">
    <div class="lesson-sidebar__progress-fill" style="width: {{ $courseProgress }}%"></div>
  </div>
  <div class="lesson-sidebar__progress-text">
    {{ $completedLessons }} / {{ $totalLessons }} bài học đã hoàn thành
  </div>
</div>

<div class="lesson-sidebar__sections">
  @foreach($course->sections as $section)
    <div class="lesson-sidebar__section" data-section-id="{{ $section->id }}">
      <div class="lesson-sidebar__section-header">
        <h3 class="lesson-sidebar__section-title">{{ $section->title }}</h3>
        <button class="lesson-sidebar__section-toggle" type="button" aria-label="Thu gọn/mở rộng">
          <i class="fas fa-chevron-down"></i>
        </button>
      </div>
      <div class="lesson-sidebar__lessons">
        @foreach($section->lessons as $l)
          @php
            $isLockedLesson = isset($lockedLessons[$l->id]);
            $isCompleted = isset($progress[$l->id]);
            $isCurrent = isset($currentLessonId) && $l->id == $currentLessonId;
          @endphp
          @if($isLockedLesson && !$isCurrent)
            <div class="lesson-item lesson-item--locked">
              <span class="lesson-item__icon lesson-item__icon--normal">🔒</span>
              <span class="lesson-item__title">{{ $l->title }}</span>
            </div>
          @else
            <a href="{{ route('student.lesson', ['course' => $course->slug, 'lesson' => $l->id]) }}" 
               class="lesson-item {{ $isCurrent ? 'lesson-item--current' : ($isCompleted ? 'lesson-item--completed' : 'lesson-item--normal') }}">
              <span class="lesson-item__icon {{ $isCompleted ? 'lesson-item__icon--completed' : ($isCurrent ? 'lesson-item__icon--current' : 'lesson-item__icon--normal') }}">
                @if($isCompleted)
                  <span>✓</span>
                @else
                  <span>{{ $loop->iteration }}</span>
                @endif
              </span>
              <span class="lesson-item__title">{{ $l->title }}</span>
              @if($l->is_preview)
                <span class="lesson-item__badge lesson-item__badge--preview">Preview</span>
              @endif
            </a>
          @endif
        @endforeach
      </div>
    </div>
  @endforeach
</div>


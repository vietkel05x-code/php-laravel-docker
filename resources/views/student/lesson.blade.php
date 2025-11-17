@extends('layouts.app')

@section('title', $lesson->title . ' - ' . $course->title)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/student-lesson.css') }}">
@endpush

@section('content')
<!-- Breadcrumb -->
<nav class="breadcrumb" style="margin-bottom: var(--spacing-lg);">
  <a href="{{ route('student.courses') }}">Khóa học của tôi</a>
  <span style="margin: 0 var(--spacing-sm);">/</span>
  <a href="{{ route('student.learn', $course->slug) }}">{{ $course->title }}</a>
  <span style="margin: 0 var(--spacing-sm);">/</span>
  <span>{{ $lesson->title }}</span>
</nav>

<section class="lesson-page">
  <div class="lesson-grid">
    <!-- Sidebar: Course Content -->
    <aside class="lesson-sidebar">
      @php
        // Calculate course progress
        $totalLessons = $course->sections->sum(function($s) {
          return $s->lessons->count();
        });
        $completedLessons = count($progress);
        $courseProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        $currentLessonId = $lesson->id;
        // Check if current lesson is already completed
        $isLessonCompleted = isset($progress[$lesson->id]);
      @endphp
      
      @include('student.partials.sidebar', ['currentLessonId' => $lesson->id])
    </aside>

    <!-- Main Content: Lesson Player -->
    <main class="lesson-main">
      <!-- Course Title -->
      <div class="lesson-course-title" style="margin-bottom: var(--spacing-md);">
        <h2 style="font-size: var(--font-size-xl); font-weight: 600; color: var(--color-text); margin: 0;">
          {{ $course->title }}
        </h2>
      </div>

      @if($isLocked)
        <div class="lesson-locked">
          <div class="lesson-locked__icon">🔒</div>
          <h2 class="lesson-locked__title">Bài học này đang bị khóa</h2>
          <p class="lesson-locked__message">{{ $lockedReason }}</p>
          @if($previousLesson)
            <a href="{{ route('student.lesson', ['course' => $course->slug, 'lesson' => $previousLesson->id]) }}" 
               class="lesson-locked__button">
              Quay lại bài học trước
            </a>
          @endif
        </div>
      @endif

      <div class="lesson-video {{ $isLocked ? 'lesson-video--locked' : '' }}">
        @php
          $videoEmbedUrl = null;
          $directVideoUrl = null;
          $useIframe = false;
          $cloudinaryDirectUrl = null;
          
          // Check for Cloudinary direct video URL first (not HLS)
          // Only set cloudinaryDirectUrl if video_url is actually a Cloudinary URL (not YouTube/other)
          if ($lesson->cloudinary_id && $lesson->video_url) {
            // Check if it's a Cloudinary URL but NOT HLS (.m3u8)
            // Cloudinary direct video URLs contain cloudinary.com but not .m3u8
            $isCloudinaryUrl = strpos($lesson->video_url, 'cloudinary.com') !== false;
            $isHlsUrl = strpos($lesson->video_url, '.m3u8') !== false;
            $isYouTubeOrVimeo = \App\Helpers\VideoHelper::isYouTube($lesson->video_url) || \App\Helpers\VideoHelper::isVimeo($lesson->video_url);
            
            // If it's Cloudinary URL but not HLS and not YouTube/Vimeo, it's a direct video URL
            if ($isCloudinaryUrl && !$isHlsUrl && !$isYouTubeOrVimeo) {
              $cloudinaryDirectUrl = $lesson->video_url;
              \Log::info('Cloudinary direct video URL detected', [
                'video_url' => $lesson->video_url,
                'cloudinary_id' => $lesson->cloudinary_id,
                'lesson_id' => $lesson->id,
              ]);
            }
          }
          
          // If not Cloudinary, check other video sources
          if (!$cloudinaryDirectUrl && $lesson->video_url) {
            // Check YouTube/Vimeo first (must use iframe)
            if (\App\Helpers\VideoHelper::shouldUseIframe($lesson->video_url)) {
              $videoEmbedUrl = \App\Helpers\VideoHelper::getEmbedUrl($lesson->video_url);
              
              // If getEmbedUrl returns null, try to extract YouTube ID manually
              if (!$videoEmbedUrl && \App\Helpers\VideoHelper::isYouTube($lesson->video_url)) {
                $youtubeId = \App\Helpers\VideoHelper::getYouTubeId($lesson->video_url);
                if ($youtubeId) {
                  $videoEmbedUrl = "https://www.youtube.com/embed/{$youtubeId}";
                  \Log::info('Manually extracted YouTube embed URL', [
                    'video_url' => $lesson->video_url,
                    'youtube_id' => $youtubeId,
                    'embed_url' => $videoEmbedUrl,
                    'lesson_id' => $lesson->id,
                  ]);
                }
              }
              
              // Only use iframe if we successfully got embed URL
              if ($videoEmbedUrl) {
                $useIframe = true;
                \Log::info('Video will be displayed in iframe', [
                  'video_url' => $lesson->video_url,
                  'embed_url' => $videoEmbedUrl,
                  'lesson_id' => $lesson->id,
                ]);
              } else {
                // Log warning if we can't extract embed URL
                \Log::warning('Cannot extract embed URL from video_url', [
                  'video_url' => $lesson->video_url,
                  'lesson_id' => $lesson->id,
                  'is_youtube' => \App\Helpers\VideoHelper::isYouTube($lesson->video_url),
                  'is_vimeo' => \App\Helpers\VideoHelper::isVimeo($lesson->video_url),
                ]);
              }
            } 
            // Check Google Drive
            elseif (\App\Helpers\VideoHelper::isGoogleDrive($lesson->video_url)) {
              $directVideoUrl = \App\Helpers\VideoHelper::getDirectVideoUrl($lesson->video_url);
            }
            // Other direct video URLs (not YouTube, not Vimeo, not Google Drive)
            else {
              // Only use as direct video URL if it's not a YouTube/Vimeo URL
              if (!\App\Helpers\VideoHelper::isYouTube($lesson->video_url) && 
                  !\App\Helpers\VideoHelper::isVimeo($lesson->video_url)) {
                $directVideoUrl = $lesson->video_url;
              } else {
                // If it's YouTube/Vimeo but shouldUseIframe didn't catch it, try again
                if (\App\Helpers\VideoHelper::isYouTube($lesson->video_url)) {
                  $youtubeId = \App\Helpers\VideoHelper::getYouTubeId($lesson->video_url);
                  if ($youtubeId) {
                    $videoEmbedUrl = "https://www.youtube.com/embed/{$youtubeId}";
                    $useIframe = true;
                    \Log::info('YouTube URL detected in else branch, created embed URL', [
                      'video_url' => $lesson->video_url,
                      'youtube_id' => $youtubeId,
                      'embed_url' => $videoEmbedUrl,
                      'lesson_id' => $lesson->id,
                    ]);
                  } else {
                    \Log::warning('YouTube URL detected but cannot extract ID', [
                      'video_url' => $lesson->video_url,
                      'lesson_id' => $lesson->id,
                    ]);
                  }
                } elseif (\App\Helpers\VideoHelper::isVimeo($lesson->video_url)) {
                  $vimeoId = \App\Helpers\VideoHelper::getVimeoId($lesson->video_url);
                  if ($vimeoId) {
                    $videoEmbedUrl = "https://player.vimeo.com/video/{$vimeoId}";
                    $useIframe = true;
                  }
                }
              }
            }
          } else {
            // Log when video_url exists but cloudinaryDirectUrl is set
            if ($lesson->video_url && $cloudinaryDirectUrl) {
              \Log::info('Using Cloudinary direct video URL, skipping other video sources', [
                'video_url' => $lesson->video_url,
                'cloudinary_direct_url' => $cloudinaryDirectUrl,
                'lesson_id' => $lesson->id,
              ]);
            } elseif (!$lesson->video_url) {
              \Log::info('No video_url found for lesson', [
                'lesson_id' => $lesson->id,
                'has_hls_path' => !empty($lesson->hls_path),
                'has_cloudinary_id' => !empty($lesson->cloudinary_id),
              ]);
            }
          }
        @endphp

        @if($cloudinaryDirectUrl)
          {{-- Cloudinary Direct Video (not HLS) - Play immediately --}}
          <video id="lessonVideo" controls controlsList="nodownload" preload="metadata" style="width: 100%; height: 100%;">
            <source src="{{ $cloudinaryDirectUrl }}" type="video/mp4">
            <source src="{{ $cloudinaryDirectUrl }}" type="video/webm">
            <div class="lesson-video__fallback">
              <p>Trình duyệt của bạn không hỗ trợ video. Vui lòng tải video về để xem.</p>
            </div>
          </video>
        @elseif($useIframe && $videoEmbedUrl)
          {{-- YouTube/Vimeo iframe embed --}}
          <div id="videoContainer" style="position: relative; width: 100%; height: 100%; min-height: 400px;">
            <iframe 
              id="lessonVideoIframe" 
              src="{{ $videoEmbedUrl }}?enablejsapi=1&origin={{ urlencode(request()->getSchemeAndHttpHost()) }}&rel=0&modestbranding=1&controls=1&showinfo=0&iv_load_policy=3&cc_load_policy=0&fs=1&playsinline=1" 
              frameborder="0" 
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
              allowfullscreen
              style="display: block; width: 100%; height: 100%; min-height: 400px;"
              loading="lazy"
              allow="fullscreen">
            </iframe>
            <div id="videoLoadingIndicator" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; text-align: center; z-index: 1;">
              <p>Đang tải video...</p>
            </div>
          </div>
          <script>
            // Debug: Log iframe creation
            console.log('YouTube/Vimeo iframe created', {
              embedUrl: '{{ $videoEmbedUrl }}',
              useIframe: {{ $useIframe ? 'true' : 'false' }},
              videoUrl: '{{ $lesson->video_url }}',
              lessonId: {{ $lesson->id }}
            });
            
            // Check if iframe loaded and handle errors
            document.addEventListener('DOMContentLoaded', function() {
              const iframe = document.getElementById('lessonVideoIframe');
              if (iframe) {
                console.log('Iframe element found in DOM', {
                  src: iframe.src,
                  width: iframe.offsetWidth,
                  height: iframe.offsetHeight,
                  parent: iframe.parentElement.className
                });
                
                // Track if iframe content loaded
                let iframeLoaded = false;
                let checkInterval = null;
                let checkCount = 0;
                const maxChecks = 10; // Check for 5 seconds (10 * 500ms)
                
                iframe.addEventListener('load', function() {
                  iframeLoaded = true;
                  console.log('YouTube/Vimeo iframe loaded successfully');
                  
                  // Hide loading indicator
                  const loadingIndicator = document.getElementById('videoLoadingIndicator');
                  if (loadingIndicator) {
                    loadingIndicator.style.display = 'none';
                  }
                  
                  if (checkInterval) {
                    clearInterval(checkInterval);
                  }
                  
                  // Check if video is actually playable (YouTube might load iframe but video is restricted)
                  setTimeout(function() {
                    // Try to detect if video is restricted by checking iframe dimensions
                    // Restricted videos often have different dimensions or show error message
                    const iframeRect = iframe.getBoundingClientRect();
                    if (iframeRect.height < 100) {
                      console.warn('Iframe height is very small, video might be restricted');
                      showVideoError('Video này có thể bị hạn chế nhúng hoặc không khả dụng. Vui lòng kiểm tra cài đặt video trên YouTube (phải bật "Cho phép nhúng").');
                    }
                  }, 3000);
                });
                
                iframe.addEventListener('error', function() {
                  console.error('YouTube/Vimeo iframe failed to load');
                  showVideoError('Không thể tải video. Vui lòng kiểm tra kết nối mạng hoặc thử lại sau.');
                });
                
                // Fallback check: if iframe doesn't load within 5 seconds, show warning
                checkInterval = setInterval(function() {
                  checkCount++;
                  if (!iframeLoaded && checkCount >= maxChecks) {
                    clearInterval(checkInterval);
                    console.warn('Iframe did not load within expected time. Video might be restricted or unavailable.');
                    
                    // Hide loading indicator
                    const loadingIndicator = document.getElementById('videoLoadingIndicator');
                    if (loadingIndicator) {
                      loadingIndicator.style.display = 'none';
                    }
                    
                    // Show error message
                    showVideoError('Video không thể tải được. Có thể video bị hạn chế nhúng, đã bị xóa, hoặc không khả dụng. Vui lòng kiểm tra lại URL video trên YouTube.');
                  }
                }, 500);
              } else {
                console.error('YouTube/Vimeo iframe not found in DOM');
              }
            });
            
            // Function to show video error message
            function showVideoError(message) {
              const iframe = document.getElementById('lessonVideoIframe');
              if (iframe && iframe.parentElement) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'lesson-video__error';
                errorDiv.style.cssText = 'padding: 20px; background: #f8d7da; color: #721c24; border-radius: 8px; text-align: center; margin-top: 10px;';
                errorDiv.innerHTML = '<p style="margin: 0;"><strong>Lỗi:</strong> ' + message + '</p>';
                iframe.parentElement.appendChild(errorDiv);
              }
            }
          </script>
        @elseif($directVideoUrl)
          <video id="lessonVideo" controls controlsList="nodownload" preload="metadata">
            <source src="{{ $directVideoUrl }}" type="video/mp4">
            <source src="{{ $directVideoUrl }}" type="video/webm">
            <source src="{{ $directVideoUrl }}" type="video/ogg">
            <div class="lesson-video__fallback">
              <p>
                Trình duyệt của bạn không hỗ trợ video. 
                @if(\App\Helpers\VideoHelper::isGoogleDrive($lesson->video_url ?? ''))
                  <br><small>Nếu video không hiển thị, vui lòng đảm bảo file Google Drive được chia sẻ công khai (Anyone with the link)</small>
                @endif
              </p>
            </div>
          </video>
        @elseif($lesson->hls_path)
          {{-- HLS Video Player --}}
          <video id="lessonVideo" controls controlsList="nodownload" preload="metadata" style="width: 100%; height: 100%;"></video>
          <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const video = document.getElementById('lessonVideo');
              const hlsUrl = '{{ Storage::url($lesson->hls_path) }}';
              
              if (Hls.isSupported()) {
                const hls = new Hls({
                  enableWorker: true,
                  lowLatencyMode: false,
                  backBufferLength: 90
                });
                hls.loadSource(hlsUrl);
                hls.attachMedia(video);
                
                hls.on(Hls.Events.MANIFEST_PARSED, function() {
                  console.log('HLS manifest parsed, video ready');
                });
                
                hls.on(Hls.Events.ERROR, function(event, data) {
                  console.error('HLS error:', data);
                  if (data.fatal) {
                    switch(data.type) {
                      case Hls.ErrorTypes.NETWORK_ERROR:
                        console.log('Fatal network error, trying to recover...');
                        hls.startLoad();
                        break;
                      case Hls.ErrorTypes.MEDIA_ERROR:
                        console.log('Fatal media error, trying to recover...');
                        hls.recoverMediaError();
                        break;
                      default:
                        console.log('Fatal error, cannot recover');
                        hls.destroy();
                        break;
                    }
                  }
                });
              } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                // Native HLS support (Safari)
                video.src = hlsUrl;
              } else {
                console.error('HLS is not supported in this browser');
                video.innerHTML = '<p>Trình duyệt của bạn không hỗ trợ HLS video.</p>';
              }
            });
          </script>
        @else
          <div class="lesson-video__fallback">
            <p>Video chưa được tải lên</p>
          </div>
        @endif
      </div>

      <!-- Warning Alert for Fast Seeking -->
      <div id="seekWarning" class="lesson-seek-warning" style="display: none;">
        <div class="lesson-seek-warning__content">
          <i class="fas fa-exclamation-triangle"></i>
          <span>⚠️ Bạn đang tua video quá nhanh. Vui lòng xem video một cách bình thường để đảm bảo chất lượng học tập.</span>
        </div>
      </div>

      <div class="lesson-info card">
        <div class="lesson-info__header">
          <div>
            <h1 class="lesson-info__title">{{ $lesson->title }}</h1>
            <p class="lesson-info__meta">
              {{ $lesson->section->title }} • {{ \App\Helpers\VideoHelper::formatDuration($lesson->duration) }}
            </p>
            <div id="videoProgress" style="margin-top: var(--spacing-sm); font-size: var(--font-size-sm); color: var(--color-text-secondary);">
              <span>Tiến độ: <span id="progressPercent">0%</span></span>
            </div>
          </div>
          @if($isLocked)
            <span class="lesson-info__status lesson-info__status--locked">
              🔒 Đang khóa
            </span>
          @elseif(!isset($progress[$lesson->id]))
            {{-- Nút đánh dấu hoàn thành đã bị ẩn - tự động hoàn thành khi xem đến 85% video --}}
            <span class="lesson-info__status lesson-info__status--in-progress" style="display: none;" id="inProgressStatus">
              ⏳ Đang xem...
            </span>
          @else
            <span class="lesson-info__status lesson-info__status--completed">
              ✓ Đã hoàn thành
            </span>
          @endif
        </div>

        @if($lesson->attachment_path)
          <div class="lesson-attachment">
            <a href="{{ Storage::url($lesson->attachment_path) }}" 
               download class="lesson-attachment__link">
              <span>📎</span>
              <span>Tải tài liệu đính kèm</span>
            </a>
          </div>
        @endif
      </div>

      <!-- Navigation -->
      <div class="lesson-navigation">
        @if($previousLesson)
          <a href="{{ route('student.lesson', ['course' => $course->slug, 'lesson' => $previousLesson->id]) }}" 
             class="lesson-navigation__link lesson-navigation__link--prev">
            ← Bài trước: {{ $previousLesson->title }}
          </a>
        @else
          <div class="lesson-navigation__spacer"></div>
        @endif

        @if($nextLesson)
          <a href="{{ route('student.lesson', ['course' => $course->slug, 'lesson' => $nextLesson->id]) }}" 
             class="lesson-navigation__link lesson-navigation__link--next">
            Bài tiếp theo: {{ $nextLesson->title }} →
          </a>
        @else
          <div class="lesson-navigation__spacer"></div>
        @endif
      </div>
    </main>
  </div>
</section>

<script>
function completeLesson() {
  // Check if already completed
  const completedStatus = document.querySelector('.lesson-info__status--completed');
  if (completedStatus) {
    return; // Already completed
  }

  fetch('{{ route("student.complete-lesson", $lesson->id) }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Update status in header
      const statusContainer = document.querySelector('.lesson-info__header');
      const inProgressStatus = document.getElementById('inProgressStatus');
      if (inProgressStatus) {
        inProgressStatus.outerHTML = '<span class="lesson-info__status lesson-info__status--completed">✓ Đã hoàn thành</span>';
      } else {
        // Find and replace status
        const statusElements = statusContainer.querySelectorAll('.lesson-info__status');
        statusElements.forEach(el => {
          if (!el.classList.contains('lesson-info__status--completed')) {
            el.outerHTML = '<span class="lesson-info__status lesson-info__status--completed">✓ Đã hoàn thành</span>';
          }
        });
      }
      
      // Update sidebar - mark lesson as completed
      const currentLessonItem = document.querySelector('.lesson-item--current');
      if (currentLessonItem) {
        currentLessonItem.classList.remove('lesson-item--current', 'lesson-item--normal');
        currentLessonItem.classList.add('lesson-item--completed');
        
        // Update icon
        const icon = currentLessonItem.querySelector('.lesson-item__icon');
        if (icon) {
          icon.classList.remove('lesson-item__icon--current', 'lesson-item__icon--normal');
          icon.classList.add('lesson-item__icon--completed');
          icon.innerHTML = '<span>✓</span>';
        }
      }
      
      // Unlock next lesson - reload sidebar via API
      fetch('{{ route("admin.api.courses.sidebar", $course->slug) }}?lesson={{ $lesson->id }}', {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.html) {
          const oldSidebar = document.querySelector('.lesson-sidebar');
          if (oldSidebar) {
            // Preserve current lesson highlight
            const currentLessonId = {{ $lesson->id }};
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            
            // Update progress section
            const newProgress = tempDiv.querySelector('.lesson-sidebar__progress');
            const oldProgress = oldSidebar.querySelector('.lesson-sidebar__progress');
            if (newProgress && oldProgress) {
              oldProgress.outerHTML = newProgress.outerHTML;
            }
            
            // Update sections
            const newSections = tempDiv.querySelector('.lesson-sidebar__sections');
            const oldSections = oldSidebar.querySelector('.lesson-sidebar__sections');
            if (newSections && oldSections) {
              // Preserve expanded/collapsed state
              const expandedSections = [];
              oldSections.querySelectorAll('.lesson-sidebar__section').forEach(section => {
                const lessons = section.querySelector('.lesson-sidebar__lessons');
                if (lessons && !lessons.classList.contains('lesson-sidebar__lessons--collapsed')) {
                  expandedSections.push(section.dataset.sectionId);
                }
              });
              
              oldSections.innerHTML = newSections.innerHTML;
              
              // Restore expanded state and highlight current lesson
              oldSections.querySelectorAll('.lesson-sidebar__section').forEach(section => {
                if (expandedSections.includes(section.dataset.sectionId)) {
                  const lessons = section.querySelector('.lesson-sidebar__lessons');
                  const toggle = section.querySelector('.lesson-sidebar__section-toggle i');
                  if (lessons) lessons.classList.remove('lesson-sidebar__lessons--collapsed');
                  if (toggle) {
                    toggle.classList.remove('fa-chevron-right');
                    toggle.classList.add('fa-chevron-down');
                  }
                }
                
                // Highlight current lesson
                const currentLink = section.querySelector(`a[href*="/lessons/{{ $lesson->id }}"]`);
                if (currentLink) {
                  currentLink.classList.add('lesson-item--current');
                  const icon = currentLink.querySelector('.lesson-item__icon');
                  if (icon) {
                    icon.classList.add('lesson-item__icon--current');
                  }
                }
              });
            }
            
            // Re-initialize collapse/expand
            initSectionToggles();
          }
        }
      })
      .catch(err => {
        console.error('Error reloading sidebar:', err);
        // Fallback: just reload page after delay
        setTimeout(() => location.reload(), 2000);
      });
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Có lỗi xảy ra khi đánh dấu hoàn thành!');
  });
}

document.addEventListener('DOMContentLoaded', function() {
      const COMPLETION_THRESHOLD = 0.98; // 98% to complete
  // Check if lesson is already completed (from server)
  const isLessonAlreadyCompleted = {{ $isLessonCompleted ? 'true' : 'false' }};
  let maxWatchedTime = 0; // Track maximum time watched
  let lastTime = 0; // Track last time for seeking detection
  let isCompleted = false;
  const SEEK_THRESHOLD_PERCENT = 0.20; // 20% of video duration
  let positionBeforeSeek = 0; // Store position before seeking

  // Handle video tag (direct video files, including Cloudinary)
  const video = document.getElementById('lessonVideo');
  if (video) {
    console.log('Video element found, setting up tracking for Cloudinary/Direct video');
    
    // Initialize lastTime when video starts playing
    let videoStarted = false;
    
    // Wait for video metadata to be loaded
    const setupVideoTracking = function() {
      console.log('Setting up video tracking, readyState:', video.readyState);
      
      // Initialize lastTime when video starts playing
      video.addEventListener('play', function() {
        if (!videoStarted) {
          lastTime = video.currentTime || 0;
          videoStarted = true;
          console.log('Video started playing, initialized lastTime:', lastTime);
        }
      }, { once: true });
      
      // Track when user starts seeking to capture position before seek
      let isSeeking = false;
      let isResetting = false; // Flag to prevent reset loop
      
      // Track video progress
      video.addEventListener('timeupdate', function() {
        if (isCompleted) return;
        
        const currentTime = video.currentTime;
        const duration = video.duration;
        
        if (duration > 0) {
          // Update max watched time
          if (currentTime > maxWatchedTime) {
            maxWatchedTime = currentTime;
          }
          
          // Calculate progress percentage
          const progress = (maxWatchedTime / duration) * 100;
          const progressElement = document.getElementById('progressPercent');
          if (progressElement) {
            progressElement.textContent = Math.round(progress) + '%';
          }
          
          // Check if reached 85%
          if (progress >= (COMPLETION_THRESHOLD * 100) && !isCompleted) {
            isCompleted = true;
            completeLesson();
          }
        }
        
        // Update lastTime continuously during playback
        // Only update if it's a normal progression (not a seek and not resetting)
        if (videoStarted && !isSeeking && !isResetting) {
          // Check if it's normal progression (not a jump)
          const timeDiff = Math.abs(currentTime - lastTime);
          if (timeDiff < 5) {
            // Normal progression - update both lastTime and positionBeforeSeek
            lastTime = currentTime;
            // During normal playback, continuously update positionBeforeSeek
            // This ensures we always have the correct position before any seek
            positionBeforeSeek = lastTime;
          } else if (timeDiff > 1) {
            // Large jump detected during timeupdate (might be from a seek that completed)
            // DON'T update lastTime or positionBeforeSeek - keep them at the position before the jump
            // This ensures that when user seeks, we capture the correct positionBeforeSeek
            console.log('Large jump detected in timeupdate, NOT updating lastTime or positionBeforeSeek (keeping original position)', {
              currentTime: currentTime,
              lastTime: lastTime,
              positionBeforeSeek: positionBeforeSeek,
              timeDiff: timeDiff,
              isSeeking: isSeeking,
              isResetting: isResetting
            });
          }
        }
      });
      
      // Detect when seeking starts - capture position BEFORE seek happens
      video.addEventListener('seeking', function() {
        // Ignore if we're in the middle of resetting
        if (isResetting) {
          console.log('Ignoring seeking event during reset');
          return;
        }
        
        if (!videoStarted) {
          // Video hasn't started yet, initialize
          lastTime = video.currentTime || 0;
          videoStarted = true;
          positionBeforeSeek = lastTime;
          isSeeking = true;
          return;
        }
        
        // Only capture position when seeking FIRST starts (not already seeking)
        // Once seeking starts, keep positionBeforeSeek fixed at the initial position
        // This prevents positionBeforeSeek from changing during rapid seeks or dragging
        if (!isSeeking) {
          // Capture the position right before seek started
          // Use lastTime which should be the most recent position from timeupdate
          // lastTime can be 0 (at start of video), so check >= 0 instead of > 0
          // Fallback to current video position if lastTime is invalid
          if (lastTime >= 0 && lastTime < video.duration) {
            positionBeforeSeek = lastTime;
          } else {
            // If lastTime is invalid, use current video position
            positionBeforeSeek = video.currentTime || 0;
          }
          isSeeking = true;
          console.log('Seeking started, captured position before seek:', positionBeforeSeek, 'lastTime:', lastTime, 'video.currentTime:', video.currentTime);
        }
        // If already seeking (rapid seeks or holding mouse), DON'T update positionBeforeSeek
        // Keep it at the original position before the first seek started
        
        const seekTarget = video.currentTime;
        const timeJump = Math.abs(seekTarget - positionBeforeSeek);
        const duration = video.duration || 0;
        const seekThreshold = duration * SEEK_THRESHOLD_PERCENT; // 20% of duration
        
        console.log('Video seeking detected', {
          seekTarget: seekTarget,
          positionBeforeSeek: positionBeforeSeek,
          lastTime: lastTime,
          timeJump: timeJump,
          duration: duration,
          seekThreshold: seekThreshold,
          isOverThreshold: timeJump > seekThreshold,
          isSeeking: isSeeking,
          isResetting: isResetting
        });
        
        // Don't reset during seeking (when dragging) - only capture position
        // Reset will happen in 'seeked' event (when mouse is released)
        // This prevents reset while user is still dragging the progress bar
      });

      // Also detect when seek completes (when mouse is released)
      video.addEventListener('seeked', function() {
        // Ignore if we're in the middle of resetting
        if (isResetting) {
          console.log('Ignoring seeked event during reset');
          return;
        }
        
        const currentTime = video.currentTime;
        const timeJump = Math.abs(currentTime - positionBeforeSeek);
        const duration = video.duration || 0;
        const seekThreshold = duration * SEEK_THRESHOLD_PERCENT;
        
        // Determine seek direction: forward (tua lên) or backward (tua xuống)
        const isForwardSeek = currentTime > positionBeforeSeek;
        const isBackwardSeek = currentTime < positionBeforeSeek;
        
        console.log('Video seeked completed (mouse released)', {
          currentTime: currentTime,
          positionBeforeSeek: positionBeforeSeek,
          lastTime: lastTime,
          timeJump: timeJump,
          seekThreshold: seekThreshold,
          isForwardSeek: isForwardSeek,
          isBackwardSeek: isBackwardSeek,
          isResetting: isResetting
        });
        
        // Only reset for FORWARD seeks (tua về phía trước) that are fast (more than 20% of duration)
        // Backward seeks (tua về phía sau) are allowed, no reset needed
        // Exception: if lesson is already completed, allow all seeks (no reset needed)
        if (isForwardSeek && timeJump > seekThreshold && duration > 0 && !isResetting && !isLessonAlreadyCompleted) {
          console.log('Fast forward seek detected (timeJump > 20% of duration), resetting to position before seek');
          
          // Set resetting flag IMMEDIATELY to prevent loop
          isResetting = true;
          isSeeking = true;
          
          // Show warning
          showSeekWarning();
          
          // Reset video to position before seek (the position user was at before seeking)
          requestAnimationFrame(function() {
            video.currentTime = positionBeforeSeek;
            lastTime = positionBeforeSeek;
            
            // Pause video to force user to restart from that position
            video.pause();
            
            console.log('Video reset to position before seek:', positionBeforeSeek);
            
            // Reset flags after a delay to ensure seeking events complete
            setTimeout(function() {
              isSeeking = false;
              isResetting = false;
              positionBeforeSeek = lastTime;
              console.log('Reset flags cleared, ready for next seek', {
                isSeeking: isSeeking,
                isResetting: isResetting,
                positionBeforeSeek: positionBeforeSeek,
                lastTime: lastTime
              });
            }, 500);
          });
          
          return;
        }
        
        // Mark that seeking is done (only if not resetting)
        if (!isResetting) {
          isSeeking = false;
          
          // Update lastTime and positionBeforeSeek after seek (seek was allowed)
          // This ensures that the next seek will use the correct positionBeforeSeek
          lastTime = currentTime;
          positionBeforeSeek = currentTime; // Update for next seek
          console.log('Seek was allowed, updating lastTime and positionBeforeSeek to:', currentTime, isBackwardSeek ? '(backward seek)' : '(forward seek, but within threshold)');
        }
      });

      // Initialize lastTime when metadata is loaded
      video.addEventListener('loadedmetadata', function() {
        if (video.currentTime > 0) {
          lastTime = video.currentTime;
          console.log('Metadata loaded, initialized lastTime from currentTime:', lastTime);
        }
      });

      // Also handle ended event as backup
      video.addEventListener('ended', function() {
        if (!isCompleted) {
          isCompleted = true;
          completeLesson();
        }
      });
    };

    // Setup tracking when video is ready
    if (video.readyState >= 1) {
      // Metadata already loaded
      setupVideoTracking();
      // Initialize lastTime if video already has currentTime
      if (video.currentTime > 0) {
        lastTime = video.currentTime;
      }
    } else {
      // Wait for metadata to load
      video.addEventListener('loadedmetadata', function() {
        setupVideoTracking();
        if (video.currentTime > 0) {
          lastTime = video.currentTime;
        }
      }, { once: true });
    }
  } else {
    console.log('Video element not found, might be YouTube iframe or HLS');
  }

  // Function to show seek warning
  function showSeekWarning() {
    console.log('showSeekWarning called');
    const warning = document.getElementById('seekWarning');
    console.log('Warning element:', warning);
    
    if (warning) {
      console.log('Showing warning, current display:', warning.style.display);
      warning.style.display = 'block';
      warning.style.visibility = 'visible';
      warning.style.opacity = '1';
      
      // Hide after 5 seconds
      setTimeout(() => {
        console.log('Hiding warning after 5 seconds');
        warning.style.display = 'none';
      }, 5000);
    } else {
      console.error('Warning element not found!');
    }
  }

  // Handle YouTube iframe
  const iframe = document.getElementById('lessonVideoIframe');
  if (iframe) {
    let youtubeIsCompleted = false;
    let player;
    let apiReady = false;
    let youtubeMaxWatchedTime = 0;
    let youtubeLastTime = 0;
    let youtubeDuration = 0;

    // Extract video ID from iframe src
    const videoId = iframe.src.match(/embed\/([^?]+)/)?.[1];
    
    if (videoId) {
      // Check if YouTube API is already loaded
      if (window.YT && window.YT.Player) {
        initializePlayer();
      } else {
        // Load YouTube IFrame API
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        tag.async = true;
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // Initialize YouTube player when API is ready
        window.onYouTubeIframeAPIReady = function() {
          apiReady = true;
          initializePlayer();
        };
      }

      function initializePlayer() {
        try {
          // Wait a bit for iframe to be ready
          setTimeout(function() {
            try {
              player = new YT.Player(iframe, {
                events: {
                  'onReady': function(event) {
                    console.log('YouTube player ready');
                    youtubeDuration = player.getDuration();
                    // Start tracking progress
                    setInterval(function() {
                      if (youtubeIsCompleted || !player) return;
                      
                      try {
                        const currentTime = player.getCurrentTime();
                        const duration = player.getDuration();
                        
                        if (duration > 0) {
                          // Update max watched time
                          if (currentTime > youtubeMaxWatchedTime) {
                            youtubeMaxWatchedTime = currentTime;
                          }
                          
                          // Calculate progress percentage
                          const progress = (youtubeMaxWatchedTime / duration) * 100;
                          const progressElement = document.getElementById('progressPercent');
                          if (progressElement) {
                            progressElement.textContent = Math.round(progress) + '%';
                          }
                          
                          // Check if reached 85%
                          if (progress >= (COMPLETION_THRESHOLD * 100) && !youtubeIsCompleted) {
                            youtubeIsCompleted = true;
                            completeLesson();
                          }
                          
                          // Detect fast seeking (jump > 30 seconds)
                          const timeJump = Math.abs(currentTime - youtubeLastTime);
                          if (timeJump > 30 && !youtubeIsCompleted) {
                            showSeekWarning();
                          }
                          
                          youtubeLastTime = currentTime;
                        }
                      } catch (e) {
                        console.error('Error tracking YouTube progress:', e);
                      }
                    }, 1000); // Check every second
                  },
                  'onStateChange': function(event) {
                    console.log('YouTube player state:', event.data);
                    // State 0 = YT.PlayerState.ENDED
                    if (event.data === YT.PlayerState.ENDED && !youtubeIsCompleted) {
                      console.log('Video ended, marking as complete');
                      youtubeIsCompleted = true;
                      completeLesson();
                    }
                  }
                }
              });
            } catch (error) {
              console.error('Error initializing YouTube player:', error);
              // Fallback: Use interval to check video progress
              startProgressCheck();
            }
          }, 500);
        } catch (error) {
          console.error('Error setting up YouTube player:', error);
          startProgressCheck();
        }
      }

      // Fallback method: Check video progress periodically
      function startProgressCheck() {
        const checkInterval = setInterval(function() {
          if (youtubeIsCompleted) {
            clearInterval(checkInterval);
            return;
          }
          
          try {
            if (player && typeof player.getCurrentTime === 'function' && typeof player.getDuration === 'function') {
              const currentTime = player.getCurrentTime();
              const duration = player.getDuration();
              
              if (duration > 0) {
                // Update max watched time
                if (currentTime > youtubeMaxWatchedTime) {
                  youtubeMaxWatchedTime = currentTime;
                }
                
                // Calculate progress percentage
                const progress = (youtubeMaxWatchedTime / duration) * 100;
                const progressElement = document.getElementById('progressPercent');
                if (progressElement) {
                  progressElement.textContent = Math.round(progress) + '%';
                }
                
                // Check if reached 85%
                if (progress >= (COMPLETION_THRESHOLD * 100) && !youtubeIsCompleted) {
                  console.log('Video reached 85%, marking as complete');
                  youtubeIsCompleted = true;
                  clearInterval(checkInterval);
                  completeLesson();
                }
                
                // Detect fast seeking
                const timeJump = Math.abs(currentTime - youtubeLastTime);
                if (timeJump > 30 && !youtubeIsCompleted) {
                  showSeekWarning();
                }
                
                youtubeLastTime = currentTime;
              }
            }
          } catch (e) {
            // Player not ready yet
          }
        }, 1000); // Check every second
      }
    }
  }

  // Section collapse/expand functionality
  function initSectionToggles() {
    document.querySelectorAll('.lesson-sidebar__section-toggle').forEach(toggle => {
      toggle.addEventListener('click', function() {
        const section = this.closest('.lesson-sidebar__section');
        const lessons = section.querySelector('.lesson-sidebar__lessons');
        const icon = this.querySelector('i');
        
        if (lessons) {
          const isExpanded = !lessons.classList.contains('lesson-sidebar__lessons--collapsed');
          
          if (isExpanded) {
            lessons.classList.add('lesson-sidebar__lessons--collapsed');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
          } else {
            lessons.classList.remove('lesson-sidebar__lessons--collapsed');
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
          }
        }
      });
    });
  }

  // Initialize section toggles on page load
  initSectionToggles();
});
</script>
@endsection

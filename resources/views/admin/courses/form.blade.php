@extends('layouts.admin')

@section('title', $course->exists ? 'Sửa khóa học' : 'Tạo khóa học')
@section('page-title', $course->exists ? 'Sửa khóa học' : 'Tạo khóa học')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<div class="admin-form-page">
  <h2 class="admin-form-page__title">{{ $course->exists ? 'Sửa khóa học' : 'Tạo khóa học' }}</h2>

  @if(session('ok') || session('success'))
    <div class="alert alert--success">{{ session('ok') ?? session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert--error">
      @foreach($errors->all() as $e) 
        <div>{{ $e }}</div>
      @endforeach
    </div>
  @endif

  <div class="admin-form-grid">
    <!-- Main Form -->
    <div>
      <form method="POST" action="{{ $course->exists ? route('admin.courses.update',$course) : route('admin.courses.store') }}" class="admin-form" enctype="multipart/form-data">
        @csrf
        @if($course->exists) @method('PUT') @endif

        <div class="admin-form__field">
          <label class="admin-form__label">Tiêu đề *</label>
          <input name="title" value="{{ old('title',$course->title) }}" class="admin-form__input" required>
        </div>

        <div class="admin-form__field">
          <label class="admin-form__label">Slug *</label>
          <input name="slug" value="{{ old('slug',$course->slug) }}" class="admin-form__input" required>
        </div>

        <div class="admin-form__field">
          <label class="admin-form__label">Danh mục</label>
          <select name="category_id" class="admin-form__select">
            <option value="">Chưa phân loại</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="admin-form__field">
          <div class="admin-form__grid admin-form__grid--2">
            <div>
              <label class="admin-form__label">Giá (₫)</label>
              <input type="number" name="price" value="{{ old('price',$course->price) }}" class="admin-form__input" min="0" step="1000">
            </div>
            <div>
              <label class="admin-form__label">Giá so sánh (₫)</label>
              <input type="number" name="compare_at_price" value="{{ old('compare_at_price',$course->compare_at_price) }}" class="admin-form__input" min="0" step="1000">
            </div>
          </div>
        </div>

        <div class="admin-form__field">
          <div class="admin-form__grid admin-form__grid--3">
            <div>
              <label class="admin-form__label">Trạng thái *</label>
              <select name="status" class="admin-form__select" required>
                <option value="draft" {{ old('status',$course->status)=='draft'?'selected':'' }}>Draft</option>
                <option value="published" {{ old('status',$course->status)=='published'?'selected':'' }}>Published</option>
                <option value="hidden" {{ old('status',$course->status)=='hidden'?'selected':'' }}>Hidden</option>
                <option value="archived" {{ old('status',$course->status)=='archived'?'selected':'' }}>Archived</option>
              </select>
            </div>
            <div>
              <label class="admin-form__label">Cấp độ</label>
              <select name="level" class="admin-form__select">
                <option value="">Chọn cấp độ</option>
                <option value="beginner" {{ old('level',$course->level)=='beginner'?'selected':'' }}>Cơ bản</option>
                <option value="intermediate" {{ old('level',$course->level)=='intermediate'?'selected':'' }}>Trung bình</option>
                <option value="advanced" {{ old('level',$course->level)=='advanced'?'selected':'' }}>Nâng cao</option>
              </select>
            </div>
            <div>
              <label class="admin-form__label">Ngôn ngữ</label>
              <input name="language" value="{{ old('language',$course->language ?? 'Vietnamese') }}" class="admin-form__input">
            </div>
          </div>
        </div>

        <div class="admin-form__field">
          <label class="admin-form__label">Tóm tắt ngắn</label>
          <textarea name="short_description" class="admin-form__textarea" rows="3">{{ old('short_description',$course->short_description) }}</textarea>
        </div>

        <div class="admin-form__field">
          <label class="admin-form__label">Ảnh thumbnail</label>
          @if($course->thumbnail_path)
            <div style="margin-bottom:8px">
              <img src="{{ $course->thumbnail_url }}" alt="Thumbnail" style="max-width:220px;border-radius:6px;border:1px solid #e5e7eb">
            </div>
          @endif
          <input type="file" name="thumbnail_file" accept="image/*" class="admin-form__input" />
          <small class="admin-help-text">Hỗ trợ JPG, PNG, GIF, WEBP. Tối đa 4MB.</small>
        </div>

        <div class="admin-form__field">
          <label class="admin-form__label">Mô tả chi tiết (HTML)</label>
          <textarea id="editor" name="description_html" rows="10">{{ old('description_html',$course->description_html) }}</textarea>
        </div>

        <div class="admin-form__actions">
          <button type="submit" class="btn btn--primary">{{ $course->exists ? 'Lưu thay đổi' : 'Tạo mới' }}</button>
          @if($course->exists)
            <a class="btn btn--outline" href="{{ route('courses.show',$course->slug) }}" target="_blank">Xem trang</a>
          @endif
        </div>
      </form>

      <!-- Sections & Lessons Management -->
      @if($course->exists)
        <div class="admin-content-management">
          <div class="admin-content-management__header">
            <h3 class="admin-content-management__title">Nội dung khóa học (Sections & Lessons)</h3>
          </div>

          @foreach($course->sections()->with('lessons')->orderBy('position')->get() as $section)
            <div class="admin-section">
              <div class="admin-section__header">
                <div class="admin-flex--1">
                  <strong class="admin-section__title">{{ $section->title }}</strong>
                  <span class="admin-section__meta">({{ $section->lessons->count() }} bài học)</span>
                </div>
                <div class="admin-section__actions">
                  <button onclick="editSection({{ $section->id }}, '{{ $section->title }}', {{ $section->position }})" 
                          class="btn btn--outline btn--sm" type="button">
                    Sửa
                  </button>
                  <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" class="admin-actions-container--inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Xóa phần này sẽ xóa tất cả bài học bên trong. Bạn có chắc?')" 
                            class="btn btn--danger btn--sm">
                      Xóa
                    </button>
                  </form>
                </div>
              </div>
              
              <div>
                @foreach($section->lessons()->orderBy('position')->get() as $lesson)
                  <div class="admin-lesson">
                    <div class="admin-lesson__content">
                      <div class="admin-lesson__title">{{ $lesson->title }}</div>
                      <div class="admin-lesson__meta">
                        {{ \App\Helpers\VideoHelper::formatDuration($lesson->duration) }}
                        @if($lesson->is_preview)
                          <span class="admin-lesson__preview">Preview</span>
                        @endif
                      </div>
                    </div>
                    <div class="admin-lesson__actions">
                      <button onclick="editLesson({{ $lesson->id }}, {{ $section->id }}, '{{ addslashes($lesson->title) }}', '{{ $lesson->video_path ?? '' }}', '{{ $lesson->video_url ?? '' }}', '{{ $lesson->attachment_path ?? '' }}', {{ $lesson->duration ?? 0 }}, {{ $lesson->is_preview ? 'true' : 'false' }}, {{ $lesson->position ?? 0 }})" 
                              class="btn btn--outline btn--sm" type="button">
                        Sửa
                      </button>
                      <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" class="admin-actions-container--inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa bài học này?')" 
                                class="btn btn--danger btn--sm">
                          Xóa
                        </button>
                      </form>
                    </div>
                  </div>
                @endforeach
                
                <div class="admin-lesson__add">
                  <button onclick="showAddLessonForm({{ $section->id }})" 
                          class="btn btn--success" type="button">
                    + Thêm bài học
                  </button>
                </div>
              </div>
            </div>
          @endforeach

          <form action="{{ route('admin.sections.store', $course) }}" method="POST" class="admin-section__add-form">
            @csrf
            <input type="text" name="title" placeholder="Tên phần mới..." required 
                   class="admin-section__add-input">
            <button type="submit" class="btn btn--primary">
              + Thêm phần
            </button>
          </form>
        </div>
      @endif
    </div>

    <!-- Sidebar -->
    <aside class="admin-form-sidebar">
      <div class="admin-form-sidebar__card">
        <h3 class="admin-form-sidebar__title">Thông tin khóa học</h3>
        @if($course->exists)
          <div class="admin-form-sidebar__item">
            <p class="admin-form-sidebar__item-label">ID</p>
            <p class="admin-form-sidebar__item-value">{{ $course->id }}</p>
          </div>
          <div class="admin-form-sidebar__item">
            <p class="admin-form-sidebar__item-label">Ngày tạo</p>
            <p class="admin-form-sidebar__item-value">{{ $course->created_at->format('d/m/Y H:i') }}</p>
          </div>
          <div class="admin-form-sidebar__item">
            <p class="admin-form-sidebar__item-label">Học viên</p>
            <p class="admin-form-sidebar__item-value">{{ $course->enrolled_students }}</p>
          </div>
          <div class="admin-form-sidebar__item">
            <p class="admin-form-sidebar__item-label">Đánh giá</p>
            <p class="admin-form-sidebar__item-value">{{ number_format($course->rating, 1) }} ⭐ ({{ $course->rating_count }})</p>
          </div>
        @endif
      </div>
    </aside>
  </div>
</div>

<!-- Modals for Section & Lesson -->
<div id="sectionModal" class="admin-modal">
  <div class="admin-modal__content admin-modal__content--small">
    <h3 class="admin-modal__title">Sửa phần</h3>
    <form id="sectionForm" method="POST">
      @csrf
      @method('PUT')
      <div class="admin-modal__field">
        <label class="admin-form__label">Tên phần</label>
        <input type="text" name="title" id="sectionTitle" required class="admin-form__input">
      </div>
      <div class="admin-modal__field admin-modal__field--last">
        <label class="admin-form__label">Vị trí</label>
        <input type="number" name="position" id="sectionPosition" class="admin-form__input">
      </div>
      <div class="admin-modal__actions">
        <button type="submit" class="admin-modal__submit">Lưu</button>
        <button type="button" onclick="document.getElementById('sectionModal').classList.remove('admin-modal--active')" class="admin-modal__cancel">Hủy</button>
      </div>
    </form>
  </div>
</div>

<div id="lessonModal" class="admin-modal">
  <div class="admin-modal__content">
    <h3 class="admin-modal__title" id="lessonModalTitle">Thêm bài học</h3>
    
    <!-- Loading Overlay -->
    <div id="lessonFormLoading" class="admin-modal__loading" style="display: none;">
      <div class="admin-modal__loading-content">
        <div class="admin-modal__spinner"></div>
        <p class="admin-modal__loading-text" id="lessonFormLoadingText">Đang xử lý...</p>
        <div class="admin-modal__progress" id="lessonFormProgress" style="display: none;">
          <div class="admin-modal__progress-bar" id="lessonFormProgressBar"></div>
          <div class="admin-modal__progress-text" id="lessonFormProgressText">0%</div>
        </div>
      </div>
    </div>
    
    <form id="lessonForm" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="section_id" id="lessonSectionId">
      <div class="admin-modal__field">
        <label class="admin-form__label">Tên bài học *</label>
        <input type="text" name="title" id="lessonTitle" required class="admin-form__input">
      </div>
      
      <!-- Video Section -->
      <div class="admin-form-section">
        <h4 class="admin-form-section__title">Video bài học</h4>
        
        <div class="admin-form-section__field">
          <label class="admin-form__label">Tải lên video file</label>
          <div class="admin-form__file-upload">
            <input type="file" name="video_file" id="lessonVideoFile" accept="video/*" class="admin-form__input admin-form__input--file">
            <div id="videoFileInfo" class="admin-form__file-info" style="display: none;">
              <div class="admin-form__file-name">
                <i class="fas fa-file-video"></i>
                <span id="videoFileName"></span>
                <span class="admin-form__file-size" id="videoFileSize"></span>
              </div>
              <div class="admin-form__file-status" id="videoFileStatus">
                <i class="fas fa-check-circle"></i> Sẵn sàng upload
              </div>
            </div>
          </div>
          <small class="admin-form-section__help">Định dạng: MP4, AVI, MOV, WMV, FLV, WEBM (tối đa 1.3GB). Video sẽ được upload lên Cloudinary hoặc convert sang HLS.</small>
        </div>
        
        <div class="admin-form-section__field">
          <label class="admin-form__label">Hoặc Link URL video (YouTube, Vimeo, Google Drive, etc.)</label>
          <div style="position: relative;">
            <input type="url" name="video_url" id="lessonVideoUrl" placeholder="https://www.youtube.com/watch?v=" 
                   class="admin-form__input">
            <span id="youtubeDurationLoader" style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #667eea;">
              <i class="fas fa-spinner fa-spin"></i> Đang lấy thời lượng...
            </span>
            <span id="youtubeDurationSuccess" style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #10b981; font-size: 12px;">
              <i class="fas fa-check-circle"></i> <span id="youtubeDurationText"></span>
            </span>
            <span id="youtubeDurationError" style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #ef4444; font-size: 12px;">
              <i class="fas fa-exclamation-circle"></i> <span id="youtubeDurationErrorText"></span>
            </span>
          </div>
          <small class="admin-form-section__help">
            Hỗ trợ: YouTube, Vimeo, Google Drive, hoặc link video trực tiếp. Thời lượng YouTube sẽ được tự động lấy.
          </small>
          <div class="admin-form-section__note">
            <strong>Lưu ý Google Drive:</strong> 
            <ul>
              <li>File phải được chia sẻ công khai (Anyone with the link)</li>
              <li>Click chuột phải vào file → "Get link" → Chọn "Anyone with the link"</li>
              <li>Định dạng hỗ trợ: https://drive.google.com/file/d/FILE_ID/view hoặc https://drive.google.com/open?id=FILE_ID</li>
              <li>Video sẽ được phát trực tiếp từ Google Drive (không dùng iframe)</li>
            </ul>
          </div>
        </div>
        
        <div class="admin-form-section__note admin-form-section__note--info">
          <strong>📌 Lưu ý:</strong>
          <ul>
            <li>Video file sẽ được upload lên <strong>Cloudinary</strong> (nếu đã cấu hình) hoặc convert sang <strong>HLS</strong> (nếu chưa có Cloudinary)</li>
            <li>Video gốc sẽ bị xóa sau khi upload/convert để tiết kiệm storage</li>
            <li>Quá trình upload có thể mất vài phút tùy vào kích thước file</li>
          </ul>
        </div>
      </div>
      
      <!-- Attachment Section -->
      <div class="admin-form-section">
        <h4 class="admin-form-section__title">Tài liệu đính kèm</h4>
        
        <div class="admin-form-section__field">
          <label class="admin-form__label">Tải lên tài liệu</label>
          <input type="file" name="attachment_file" id="lessonAttachmentFile" 
                 accept=".pdf,.doc,.docx,.zip,.rar" 
                 class="admin-form__input">
          <small class="admin-form-section__help">Định dạng: PDF, DOC, DOCX, ZIP, RAR (tối đa 1.3GB)</small>
        </div>
        
        <div class="admin-form-section__field admin-form-section__field--last">
          <label class="admin-form__label">Hoặc đường dẫn file đã có (tùy chọn)</label>
          <input type="text" name="attachment_path" id="lessonAttachmentPath" placeholder="attachments/file.pdf" 
                 class="admin-form__input">
          <small class="admin-form-section__help">Đường dẫn file trong storage nếu đã upload trước đó</small>
        </div>
      </div>
      
      <div class="admin-modal__field">
        <div class="admin-form__grid admin-form__grid--2">
          <div>
            <label class="admin-form__label">Thời lượng (phút)</label>
            <input type="number" name="duration" id="lessonDuration" min="0" value="0" class="admin-form__input">
            <small class="admin-form-section__help">
              Tự động tính từ video file hoặc YouTube API.
            </small>
          </div>
          <div>
            <label class="admin-form__label">Vị trí</label>
            <input type="number" name="position" id="lessonPosition" min="0" class="admin-form__input">
            <small class="admin-form-section__help">
              Tự động đặt ở cuối nếu để trống. Vị trí xác định thứ tự hiển thị bài học.
            </small>
          </div>
        </div>
      </div>
      
      <div class="admin-modal__field admin-modal__field--last">
        <label class="admin-flex admin-flex--center-items admin-flex--gap-sm" style="cursor: pointer;">
          <input type="checkbox" name="is_preview" id="lessonIsPreview">
          <span>Cho phép xem trước (Preview)</span>
        </label>
      </div>
      
      <div class="admin-modal__actions">
        <button type="submit" class="admin-modal__submit" id="lessonFormSubmit">
          <span class="admin-modal__submit-text">Lưu</span>
          <span class="admin-modal__submit-loading" style="display: none;">
            <i class="fas fa-spinner fa-spin"></i> Đang xử lý...
          </span>
        </button>
        <button type="button" onclick="closeLessonModal()" class="admin-modal__cancel" id="lessonFormCancel">Hủy</button>
      </div>
    </form>
  </div>
</div>

<script>
function editSection(id, title, position) {
  document.getElementById('sectionForm').action = `/admin/sections/${id}`;
  document.getElementById('sectionTitle').value = title;
  document.getElementById('sectionPosition').value = position;
  document.getElementById('sectionModal').classList.add('admin-modal--active');
}

// Close modal when clicking outside
document.getElementById('sectionModal')?.addEventListener('click', function(e) {
  if (e.target === this) {
    this.classList.remove('admin-modal--active');
  }
});

document.getElementById('lessonModal')?.addEventListener('click', function(e) {
  if (e.target === this) {
    this.classList.remove('admin-modal--active');
  }
});

function showAddLessonForm(sectionId) {
  try {
    console.log('showAddLessonForm called with sectionId:', sectionId);
    document.getElementById('lessonModalTitle').textContent = 'Thêm bài học';
    const form = document.getElementById('lessonForm');
    if (!form) {
      console.error('lessonForm not found');
      return;
    }
    form.action = `/admin/sections/${sectionId}/lessons`;
    form.method = 'POST';
    form.enctype = 'multipart/form-data';
    
    // Remove _method if exists
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();
    
    document.getElementById('lessonSectionId').value = sectionId;
    document.getElementById('lessonTitle').value = '';
    
    const videoFileInput = document.getElementById('lessonVideoFile');
    if (videoFileInput) videoFileInput.value = '';
    
    document.getElementById('lessonVideoUrl').value = '';
    
    const videoPathInput = document.getElementById('lessonVideoPath');
    if (videoPathInput) videoPathInput.value = '';
    
    const attachmentFileInput = document.getElementById('lessonAttachmentFile');
    if (attachmentFileInput) attachmentFileInput.value = '';
    
    const attachmentPathInput = document.getElementById('lessonAttachmentPath');
    if (attachmentPathInput) attachmentPathInput.value = '';
    
    document.getElementById('lessonDuration').value = '';
    document.getElementById('lessonPosition').value = '';
    document.getElementById('lessonIsPreview').checked = false;
    
    // Reset file info
    const videoFileInfo = document.getElementById('videoFileInfo');
    if (videoFileInfo) videoFileInfo.style.display = 'none';
    
    // Reset loading state
    const loadingOverlay = document.getElementById('lessonFormLoading');
    if (loadingOverlay) loadingOverlay.style.display = 'none';
    
    const submitBtn = document.getElementById('lessonFormSubmit');
    if (submitBtn) {
      const submitText = submitBtn.querySelector('.admin-modal__submit-text');
      const submitLoading = submitBtn.querySelector('.admin-modal__submit-loading');
      if (submitText) submitText.style.display = 'inline';
      if (submitLoading) submitLoading.style.display = 'none';
      submitBtn.disabled = false;
    }
    
    const cancelBtn = document.getElementById('lessonFormCancel');
    if (cancelBtn) cancelBtn.disabled = false;
    
    // Reset YouTube duration indicators
    if (typeof resetYouTubeDurationIndicators === 'function') {
      resetYouTubeDurationIndicators();
    }
    
    const modal = document.getElementById('lessonModal');
    if (modal) {
      modal.classList.add('admin-modal--active');
      console.log('Modal opened');
    } else {
      console.error('lessonModal not found');
    }
  } catch (error) {
    console.error('Error in showAddLessonForm:', error);
    alert('Có lỗi xảy ra khi mở form thêm bài học. Vui lòng kiểm tra console để biết chi tiết.');
  }
}

function editLesson(id, sectionId, title, videoPath, videoUrl, attachmentPath, duration, isPreview, position) {
  try {
    console.log('editLesson called with id:', id);
    document.getElementById('lessonModalTitle').textContent = 'Sửa bài học';
    const form = document.getElementById('lessonForm');
    if (!form) {
      console.error('lessonForm not found');
      return;
    }
    form.action = `/admin/lessons/${id}`;
    form.method = 'POST';
    form.enctype = 'multipart/form-data';
    
    // Add _method for PUT
    if (!form.querySelector('input[name="_method"]')) {
      const methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      methodInput.value = 'PUT';
      form.appendChild(methodInput);
    }
    
    document.getElementById('lessonSectionId').value = sectionId;
    document.getElementById('lessonTitle').value = title || '';
    
    // video_path field may not exist anymore (removed from form)
    const videoPathInput = document.getElementById('lessonVideoPath');
    if (videoPathInput) {
      videoPathInput.value = videoPath || '';
    }
    
    document.getElementById('lessonVideoUrl').value = videoUrl || '';
    
    const attachmentPathInput = document.getElementById('lessonAttachmentPath');
    if (attachmentPathInput) {
      attachmentPathInput.value = attachmentPath || '';
    }
    
    document.getElementById('lessonDuration').value = duration || '';
    document.getElementById('lessonPosition').value = position || '';
    document.getElementById('lessonIsPreview').checked = isPreview;
    
    // Reset file info
    const videoFileInfo = document.getElementById('videoFileInfo');
    if (videoFileInfo) videoFileInfo.style.display = 'none';
    const videoFileInput = document.getElementById('lessonVideoFile');
    if (videoFileInput) videoFileInput.value = '';
    
    // Reset loading state
    const loadingOverlay = document.getElementById('lessonFormLoading');
    if (loadingOverlay) loadingOverlay.style.display = 'none';
    
    const submitBtn = document.getElementById('lessonFormSubmit');
    if (submitBtn) {
      const submitText = submitBtn.querySelector('.admin-modal__submit-text');
      const submitLoading = submitBtn.querySelector('.admin-modal__submit-loading');
      if (submitText) submitText.style.display = 'inline';
      if (submitLoading) submitLoading.style.display = 'none';
      submitBtn.disabled = false;
    }
    
    const cancelBtn = document.getElementById('lessonFormCancel');
    if (cancelBtn) cancelBtn.disabled = false;
    
    // Reset YouTube duration indicators
    if (typeof resetYouTubeDurationIndicators === 'function') {
      resetYouTubeDurationIndicators();
    }
    
    // If YouTube URL exists, try to fetch duration
    if (videoUrl && typeof isYouTubeUrl === 'function' && typeof fetchYouTubeDuration === 'function') {
      if (isYouTubeUrl(videoUrl)) {
        fetchYouTubeDuration(videoUrl);
      }
    }
    
    const modal = document.getElementById('lessonModal');
    if (modal) {
      modal.classList.add('admin-modal--active');
      console.log('Modal opened');
    } else {
      console.error('lessonModal not found');
    }
  } catch (error) {
    console.error('Error in editLesson:', error);
    alert('Có lỗi xảy ra khi mở form sửa bài học. Vui lòng kiểm tra console để biết chi tiết.');
  }
}

// YouTube Duration Auto-fetch
function isYouTubeUrl(url) {
  if (!url) return false;
  return /youtube\.com|youtu\.be/.test(url);
}

function resetYouTubeDurationIndicators() {
  document.getElementById('youtubeDurationLoader').style.display = 'none';
  document.getElementById('youtubeDurationSuccess').style.display = 'none';
  document.getElementById('youtubeDurationError').style.display = 'none';
}

function fetchYouTubeDuration(videoUrl) {
  if (!videoUrl || !isYouTubeUrl(videoUrl)) {
    return;
  }

  const loader = document.getElementById('youtubeDurationLoader');
  const success = document.getElementById('youtubeDurationSuccess');
  const error = document.getElementById('youtubeDurationError');
  const errorText = document.getElementById('youtubeDurationErrorText');
  const durationText = document.getElementById('youtubeDurationText');
  const durationInput = document.getElementById('lessonDuration');

  // Reset indicators
  resetYouTubeDurationIndicators();
  loader.style.display = 'block';

  // Call API
  fetch('{{ route("admin.api.youtube.duration") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ video_url: videoUrl })
  })
  .then(response => response.json())
  .then(data => {
    loader.style.display = 'none';
    
    if (data.duration && data.duration > 0) {
      // Success - auto-fill duration
      durationInput.value = data.duration;
      durationText.textContent = data.formatted || `${data.duration} phút`;
      success.style.display = 'block';
      
      // Hide success message after 3 seconds
      setTimeout(() => {
        success.style.display = 'none';
      }, 3000);
    } else {
      // Error
      errorText.textContent = data.error || 'Không thể lấy thời lượng';
      error.style.display = 'block';
    }
  })
  .catch(err => {
    loader.style.display = 'none';
    errorText.textContent = 'Lỗi kết nối API';
    error.style.display = 'block';
    console.error('YouTube API error:', err);
  });
}

// Auto-fetch duration when YouTube URL is entered
document.addEventListener('DOMContentLoaded', function() {
  const videoUrlInput = document.getElementById('lessonVideoUrl');
  if (videoUrlInput) {
    let debounceTimer;
    
    videoUrlInput.addEventListener('input', function() {
      const url = this.value.trim();
      
      // Clear previous timer
      clearTimeout(debounceTimer);
      
      // Reset indicators if URL is cleared
      if (!url) {
        resetYouTubeDurationIndicators();
        return;
      }
      
      // Debounce: wait 1 second after user stops typing
      debounceTimer = setTimeout(() => {
        if (isYouTubeUrl(url)) {
          fetchYouTubeDuration(url);
        } else {
          resetYouTubeDurationIndicators();
        }
      }, 1000);
    });
    
    // Also fetch on blur (when user leaves the field)
    videoUrlInput.addEventListener('blur', function() {
      const url = this.value.trim();
      if (url && isYouTubeUrl(url)) {
        fetchYouTubeDuration(url);
      }
    });
  }
  
  // File upload handler
  const videoFileInput = document.getElementById('lessonVideoFile');
  if (videoFileInput) {
    videoFileInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const fileInfo = document.getElementById('videoFileInfo');
        const fileName = document.getElementById('videoFileName');
        const fileSize = document.getElementById('videoFileSize');
        const fileStatus = document.getElementById('videoFileStatus');
        
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.style.display = 'block';
        fileStatus.innerHTML = '<i class="fas fa-check-circle"></i> Sẵn sàng upload';
        fileStatus.className = 'admin-form__file-status admin-form__file-status--ready';
      } else {
        document.getElementById('videoFileInfo').style.display = 'none';
      }
    });
  }
  
  // Form submit handler with loading and progress bar
  const lessonForm = document.getElementById('lessonForm');
  if (lessonForm) {
    lessonForm.addEventListener('submit', function(e) {
      const videoFile = document.getElementById('lessonVideoFile').files[0];
      const hasVideoFile = videoFile && videoFile.size > 0;
      
      if (hasVideoFile) {
        e.preventDefault(); // Prevent default form submit
        
        // Show loading overlay
        const loadingOverlay = document.getElementById('lessonFormLoading');
        const loadingText = document.getElementById('lessonFormLoadingText');
        const progressContainer = document.getElementById('lessonFormProgress');
        const progressBar = document.getElementById('lessonFormProgressBar');
        const progressText = document.getElementById('lessonFormProgressText');
        const submitBtn = document.getElementById('lessonFormSubmit');
        const submitText = submitBtn.querySelector('.admin-modal__submit-text');
        const submitLoading = submitBtn.querySelector('.admin-modal__submit-loading');
        
        loadingOverlay.style.display = 'flex';
        progressContainer.style.display = 'block';
        progressBar.style.setProperty('--progress-width', '0%');
        if (progressText) progressText.textContent = '0%';
        loadingText.textContent = 'Đang upload video...';
        submitText.style.display = 'none';
        submitLoading.style.display = 'inline-flex';
        submitBtn.disabled = true;
        document.getElementById('lessonFormCancel').disabled = true;
        
        // Get form data
        const formData = new FormData(lessonForm);
        const formAction = lessonForm.action;
        const formMethod = lessonForm.method;
        
        // Create XMLHttpRequest for progress tracking
        const xhr = new XMLHttpRequest();
        
        // Track upload progress
        let uploadComplete = false;
        xhr.upload.addEventListener('progress', function(e) {
          if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            
            // Update progress bar using CSS variable (only up to 80% for upload)
            // Reserve 20% for server processing
            const uploadPercent = Math.min(percentComplete, 80);
            progressBar.style.setProperty('--progress-width', uploadPercent + '%');
            
            // Update progress text
            if (progressText) {
              progressText.textContent = uploadPercent + '%';
            }
            
            // Update loading text
            loadingText.textContent = `Đang upload video... ${uploadPercent}%`;
            
            if (percentComplete >= 100) {
              uploadComplete = true;
            }
          }
        });
        
        // Handle upload complete (before server processing)
        xhr.upload.addEventListener('load', function() {
          // Upload finished, now server is processing
          uploadComplete = true;
          loadingText.textContent = 'Upload hoàn tất! Đang xử lý video (Cloudinary/HLS)...';
          
          // Animate progress from 80% to 95% while processing
          let processingPercent = 80;
          const processingInterval = setInterval(() => {
            processingPercent = Math.min(processingPercent + 1, 95);
            progressBar.style.setProperty('--progress-width', processingPercent + '%');
            if (progressText) {
              progressText.textContent = processingPercent + '%';
            }
            
            // Stop when response received
            if (xhr.readyState === 4) {
              clearInterval(processingInterval);
            }
          }, 500);
        });
        
        // Handle completion
        xhr.addEventListener('load', function() {
          // Check if response is successful (200-299) or redirect (300-399)
          if (xhr.status >= 200 && xhr.status < 400) {
            // Success - show 100% and reload
            progressBar.style.setProperty('--progress-width', '100%');
            if (progressText) progressText.textContent = '100%';
            loadingText.textContent = 'Hoàn tất! Đang tải lại trang...';
            
            // If response contains redirect URL, use it
            const responseText = xhr.responseText;
            if (xhr.status === 302 || responseText.includes('window.location')) {
              // Extract redirect URL if present
              const redirectMatch = responseText.match(/window\.location\s*=\s*['"]([^'"]+)['"]/);
              if (redirectMatch) {
                setTimeout(() => {
                  window.location.href = redirectMatch[1];
                }, 1000);
              } else {
                setTimeout(() => {
                  window.location.reload();
                }, 1000);
              }
            } else {
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            }
          } else {
            // Error
            loadingText.textContent = 'Có lỗi xảy ra. Vui lòng thử lại.';
            progressBar.style.setProperty('--progress-width', '0%');
            if (progressText) progressText.textContent = '0%';
            submitBtn.disabled = false;
            document.getElementById('lessonFormCancel').disabled = false;
            submitText.style.display = 'inline';
            submitLoading.style.display = 'none';
            
            // Show error message
            alert('Có lỗi xảy ra khi upload video. Vui lòng kiểm tra console để biết chi tiết.');
            console.error('Upload error:', xhr.status, xhr.responseText);
          }
        });
        
        // Handle errors
        xhr.addEventListener('error', function() {
          loadingText.textContent = 'Lỗi kết nối. Vui lòng thử lại.';
          progressBar.style.setProperty('--progress-width', '0%');
          if (progressText) progressText.textContent = '0%';
          submitBtn.disabled = false;
          document.getElementById('lessonFormCancel').disabled = false;
          submitText.style.display = 'inline';
          submitLoading.style.display = 'none';
        });
        
        // Send request
        xhr.open(formMethod, formAction);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        // Add CSRF token if exists
        const csrfToken = document.querySelector('input[name="_token"]');
        if (csrfToken) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.value);
        }
        
        xhr.send(formData);
      }
    });
  }
});

// Format file size
function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Close modal function
function closeLessonModal() {
  const modal = document.getElementById('lessonModal');
  if (modal) {
    modal.classList.remove('admin-modal--active');
    // Reset form
    document.getElementById('lessonForm').reset();
    document.getElementById('videoFileInfo').style.display = 'none';
    resetYouTubeDurationIndicators();
    // Reset loading state
    document.getElementById('lessonFormLoading').style.display = 'none';
    const submitBtn = document.getElementById('lessonFormSubmit');
    if (submitBtn) {
      submitBtn.querySelector('.admin-modal__submit-text').style.display = 'inline';
      submitBtn.querySelector('.admin-modal__submit-loading').style.display = 'none';
      submitBtn.disabled = false;
    }
    const cancelBtn = document.getElementById('lessonFormCancel');
    if (cancelBtn) cancelBtn.disabled = false;
  }
}
</script>

{{-- CKEditor 5 CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
ClassicEditor.create(document.querySelector('#editor'), {
  toolbar: ['heading','|','bold','italic','link','bulletedList','numberedList','blockQuote','|','undo','redo']
}).catch(console.error);
</script>
@endsection

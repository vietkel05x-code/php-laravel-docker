@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-page">
  <div class="admin-page-header">
    <h1 class="admin-page-header__title">Quản lý đánh giá</h1>
  </div>

  @if(session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
  @endif

  <!-- Filters -->
  <form method="GET" action="{{ route('admin.reviews.index') }}" class="admin-filters card admin-mb--lg">
    <div class="admin-form__grid admin-form__grid--auto">
      <div>
        <label class="admin-form__label">Tìm kiếm</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên, email, khóa học..." 
               class="admin-form__input">
      </div>
      <div>
        <label class="admin-form__label">Khóa học</label>
        <select name="course_id" class="admin-form__select">
          <option value="">Tất cả</option>
          @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
              {{ $course->title }}
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="admin-form__label">Đánh giá</label>
        <select name="rating" class="admin-form__select">
          <option value="">Tất cả</option>
          @for($i = 5; $i >= 1; $i--)
            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
              {{ $i }} sao
            </option>
          @endfor
        </select>
      </div>
      <div class="admin-flex admin-flex--center-items" style="align-items: flex-end; gap: var(--spacing-sm);">
        <button type="submit" class="btn btn--primary">Lọc</button>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn--outline">Reset</a>
      </div>
    </div>
  </form>

  <!-- Reviews Table -->
  @if($reviews->count() > 0)
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Người dùng</th>
            <th>Khóa học</th>
            <th class="admin-table__cell--center">Đánh giá</th>
            <th>Nội dung</th>
            <th>Ngày</th>
            <th class="admin-table__cell--center">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @foreach($reviews as $review)
            <tr>
              <td>
                <div class="admin-text--bold">{{ $review->user->name }}</div>
                <div class="admin-text--small admin-text--secondary">{{ $review->user->email }}</div>
              </td>
              <td>
                <a href="{{ route('courses.show', $review->course->slug) }}" target="_blank" class="admin-table__link">
                  {{ $review->course->title }}
                </a>
              </td>
              <td class="admin-table__cell--center">
                <div class="admin-flex admin-flex--center-items admin-flex--center admin-flex--gap-sm">
                  @for($i = 1; $i <= 5; $i++)
                    <i class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }}" style="color: #f3ca8c;"></i>
                  @endfor
                </div>
              </td>
              <td style="max-width: 300px;">
                <p class="admin-text--secondary" style="margin: 0;">
                  {{ Str::limit($review->content ?? 'Không có nhận xét', 100) }}
                </p>
              </td>
              <td class="admin-table__cell--secondary">
                {{ $review->created_at->format('d/m/Y H:i') }}
              </td>
              <td class="admin-table__cell--center">
                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="admin-actions-container--inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')" 
                          class="btn btn--danger btn--sm">
                    Xóa
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="admin-pagination">
      {{ $reviews->links() }}
    </div>
  @else
    <div class="admin-card card">
      <p class="admin-empty-state">
        Không tìm thấy đánh giá nào
      </p>
    </div>
  @endif
</section>
@endsection

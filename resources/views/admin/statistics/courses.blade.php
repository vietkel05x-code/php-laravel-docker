@extends('layouts.admin')

@section('title', 'Thống kê khóa học')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-statistics-page">
  <h1 class="admin-statistics-page__title">Thống kê khóa học</h1>

  <!-- Summary Cards -->
  <div class="admin-summary-cards">
    <div class="admin-summary-card">
      <p class="admin-summary-card__label">Tổng khóa học</p>
      <p class="admin-summary-card__value admin-summary-card__value--primary">{{ $totalCourses }}</p>
      <p class="admin-summary-card__meta">{{ $publishedCourses }} đã xuất bản</p>
    </div>
    <div class="admin-summary-card">
      <p class="admin-summary-card__label">Tổng lượt ghi danh</p>
      <p class="admin-summary-card__value admin-summary-card__value--success">{{ $totalEnrollments }}</p>
      <p class="admin-summary-card__meta">Trung bình {{ $avgEnrollmentsPerCourse }} / khóa học</p>
    </div>
    <div class="admin-summary-card">
      <p class="admin-summary-card__label">Khóa học đã xuất bản</p>
      <p class="admin-summary-card__value admin-summary-card__value--info">{{ $publishedCourses }}</p>
      <p class="admin-summary-card__meta">{{ $draftCourses }} bản nháp</p>
    </div>
  </div>

  <!-- Top Courses by Enrollment -->
  <div class="admin-card">
    <h2 class="admin-card__title admin-mb--lg">Top khóa học theo lượt ghi danh</h2>
    @if($topCoursesByEnrollment->count() > 0)
      <div style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Khóa học</th>
              <th>Danh mục</th>
              <th>Giảng viên</th>
              <th class="admin-table__cell--center">Lượt ghi danh</th>
              <th class="admin-table__cell--center">Đánh giá</th>
              <th class="admin-table__cell--right">Giá</th>
            </tr>
          </thead>
          <tbody>
            @foreach($topCoursesByEnrollment as $course)
              <tr>
                <td class="admin-table__cell--bold">{{ $course->title }}</td>
                <td>{{ $course->category->name ?? '-' }}</td>
                <td>{{ $course->instructor->name ?? '-' }}</td>
                <td class="admin-table__cell--center admin-table__cell--bold">{{ $course->enrolled_students }}</td>
                <td class="admin-table__cell--center">
                  @if($course->rating > 0)
                    {{ number_format($course->rating, 1) }} ⭐ ({{ $course->rating_count ?? 0 }} đánh giá)
                  @else
                    Chưa có đánh giá
                  @endif
                </td>
                <td class="admin-table__cell--right admin-table__cell--bold">₫{{ number_format($course->price, 0, ',', '.') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="admin-card__empty">Chưa có khóa học nào</p>
    @endif
  </div>

  <!-- Top Courses by Rating -->
  <div class="admin-card">
    <h2 class="admin-card__title admin-mb--lg">Top khóa học theo đánh giá</h2>
    @if($topCoursesByRating->count() > 0)
      <div style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Khóa học</th>
              <th>Danh mục</th>
              <th class="admin-table__cell--center">Đánh giá</th>
              <th class="admin-table__cell--center">Số đánh giá</th>
              <th class="admin-table__cell--center">Lượt ghi danh</th>
            </tr>
          </thead>
          <tbody>
            @foreach($topCoursesByRating as $course)
              <tr>
                <td class="admin-table__cell--bold">{{ $course->title }}</td>
                <td>{{ $course->category->name ?? '-' }}</td>
                <td class="admin-table__cell--center admin-table__cell--bold" style="font-size: var(--font-size-lg);">
                  {{ number_format($course->rating, 1) }} ⭐
                </td>
                <td class="admin-table__cell--center">{{ $course->rating_count ?? 0 }}</td>
                <td class="admin-table__cell--center">{{ $course->enrolled_students }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="admin-card__empty">Chưa có khóa học nào có đánh giá</p>
    @endif
  </div>

  <!-- Courses by Category -->
  <div class="admin-card">
    <h2 class="admin-card__title admin-mb--lg">Phân bố khóa học theo danh mục</h2>
    @if($coursesByCategory->count() > 0)
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: var(--spacing-md);">
        @foreach($coursesByCategory as $item)
          <div style="padding: var(--spacing-md); background: var(--color-bg-light); border-radius: var(--radius-md);">
            <p style="margin: 0; font-weight: bold; font-size: var(--font-size-md);">{{ $item->category->name ?? 'Chưa phân loại' }}</p>
            <p style="margin: var(--spacing-sm) 0 0 0; font-size: var(--font-size-2xl); color: var(--color-primary); font-weight: bold;">{{ $item->count }} khóa học</p>
          </div>
        @endforeach
      </div>
    @else
      <p class="admin-card__empty">Chưa có dữ liệu</p>
    @endif
  </div>

  <!-- Completion Statistics -->
  @if(count($completionStats) > 0)
    <div class="admin-card">
      <h2 class="admin-card__title admin-mb--lg">Tỉ lệ hoàn thành khóa học</h2>
      <div style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Khóa học</th>
              <th class="admin-table__cell--center">Lượt ghi danh</th>
              <th class="admin-table__cell--center">Tỉ lệ hoàn thành</th>
              <th class="admin-table__cell--center">Thanh toán</th>
            </tr>
          </thead>
          <tbody>
            @foreach($completionStats as $stat)
              <tr>
                <td class="admin-table__cell--bold">{{ $stat['course']->title }}</td>
                <td class="admin-table__cell--center">{{ $stat['enrollments'] }}</td>
                <td class="admin-table__cell--center">
                  <div class="admin-flex admin-flex--center-items admin-flex--center admin-flex--gap-sm">
                    <div style="flex: 1; max-width: 200px; height: 8px; background: #e9ecef; border-radius: var(--radius-sm); overflow: hidden;">
                      <div style="height: 100%; background: var(--color-success); width: {{ $stat['completion_rate'] }}%;"></div>
                    </div>
                    <span class="admin-table__cell--bold" style="min-width: 50px;">{{ $stat['completion_rate'] }}%</span>
                  </div>
                </td>
                <td class="admin-table__cell--center">₫{{ number_format($stat['course']->price, 0, ',', '.') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
</section>
@endsection

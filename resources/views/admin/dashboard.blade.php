@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-page">
  <h1 class="admin-page__title">Bảng điều khiển</h1>

  <!-- Statistics Cards -->
  <div class="admin-stats">
    <div class="admin-stat-card card">
      <div class="admin-stat-card__header">
        <div class="admin-stat-card__content">
          <p class="admin-stat-card__label">Tổng khóa học</p>
          <p class="admin-stat-card__value admin-stat-card__value--primary">{{ $stats['total_courses'] }}</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--primary">📚</div>
      </div>
      <p class="admin-stat-card__footer">{{ $stats['published_courses'] }} đã xuất bản</p>
    </div>

    <div class="admin-stat-card card">
      <div class="admin-stat-card__header">
        <div class="admin-stat-card__content">
          <p class="admin-stat-card__label">Tổng người dùng</p>
          <p class="admin-stat-card__value admin-stat-card__value--success">{{ $stats['total_users'] }}</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--success">👥</div>
      </div>
      <p class="admin-stat-card__footer">{{ $stats['total_students'] }} học viên</p>
    </div>

    <div class="admin-stat-card card">
      <div class="admin-stat-card__header">
        <div class="admin-stat-card__content">
          <p class="admin-stat-card__label">Tổng doanh thu</p>
          <p class="admin-stat-card__value admin-stat-card__value--info">₫{{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--info">💰</div>
      </div>
      <p class="admin-stat-card__footer">{{ $stats['total_orders'] }} đơn hàng</p>
    </div>

    <div class="admin-stat-card card">
      <div class="admin-stat-card__header">
        <div class="admin-stat-card__content">
          <p class="admin-stat-card__label">Tổng đăng ký</p>
          <p class="admin-stat-card__value admin-stat-card__value--warning">{{ $stats['total_enrollments'] }}</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--warning">✅</div>
      </div>
      <p class="admin-stat-card__footer">{{ $stats['total_reviews'] }} đánh giá</p>
    </div>
  </div>

  <div class="admin-dashboard-grid">
    <!-- Recent Orders -->
    <div class="admin-card card">
      <div class="admin-card__header">
        <h2 class="admin-card__title">Đơn hàng gần đây</h2>
        <a href="{{ route('admin.courses.index') }}" class="admin-card__link">Xem tất cả</a>
      </div>
      @if($recentOrders->count() > 0)
        <div class="admin-card__list">
          @foreach($recentOrders as $order)
            <div class="admin-card__item">
              <div class="admin-card__item-header">
                <div>
                  <p class="admin-card__item-title">#{{ $order->code }}</p>
                  <p class="admin-card__item-meta">{{ $order->user->name }}</p>
                </div>
                <div class="admin-order-item__value">
                  <p class="admin-card__item-value">₫{{ number_format($order->total, 0, ',', '.') }}</p>
                  <p class="admin-card__item-date">{{ $order->created_at->format('d/m/Y') }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p class="admin-card__empty">Chưa có đơn hàng nào</p>
      @endif
    </div>

    <!-- Top Courses -->
    <div class="admin-card card">
      <div class="admin-card__header">
        <h2 class="admin-card__title">Khóa học phổ biến</h2>
        <a href="{{ route('admin.courses.index') }}" class="admin-card__link">Xem tất cả</a>
      </div>
      @if($topCourses->count() > 0)
        <div class="admin-card__list">
          @foreach($topCourses as $course)
            <div class="admin-card__item">
              <div class="admin-card__item-header">
                <div class="admin-flex--1">
                  <p class="admin-card__item-title">{{ Str::limit($course->title, 40) }}</p>
                  <p class="admin-card__item-meta">
                    {{ $course->enrolled_students }} học viên · {{ number_format($course->rating, 1) }} ⭐
                  </p>
                </div>
                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn--primary btn--sm">
                  Sửa
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p class="admin-card__empty">Chưa có khóa học nào</p>
      @endif
    </div>
  </div>

  <!-- Statistics Links -->
  <div class="admin-card card admin-mb--xl">
    <h2 class="admin-card__title admin-mb--lg">Thống kê chi tiết</h2>
    <div class="admin-actions">
      <a href="{{ route('admin.statistics.revenue') }}" class="admin-action admin-action--info">
        📊 Thống kê doanh thu
      </a>
      <a href="{{ route('admin.statistics.courses') }}" class="admin-action admin-action--primary">
        📚 Thống kê khóa học
      </a>
      <a href="{{ route('admin.statistics.students') }}" class="admin-action admin-action--success">
        👥 Thống kê người học
      </a>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="admin-card card">
    <h2 class="admin-card__title admin-mb--lg">Thao tác nhanh</h2>
    <div class="admin-actions">
      <a href="{{ route('admin.courses.create') }}" class="admin-action admin-action--primary">
        + Tạo khóa học
      </a>
      <a href="{{ route('admin.categories.create') }}" class="admin-action admin-action--success">
        + Tạo danh mục
      </a>
      <a href="{{ route('admin.coupons.create') }}" class="admin-action admin-action--info">
        + Tạo mã giảm giá
      </a>
      <a href="{{ route('admin.users.create') }}" class="admin-action admin-action--secondary">
        + Tạo người dùng
      </a>
    </div>
  </div>
</section>
@endsection

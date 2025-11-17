@extends('layouts.admin')

@section('title', 'Bảng điều khiển')
@section('page-title', 'Bảng điều khiển')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="admin-stats">
  <div class="admin-stat-card card admin-stat-card--border-primary">
    <div class="admin-stat-card__header">
      <div class="admin-stat-card__content">
        <p class="admin-stat-card__label">Tổng khóa học</p>
        <p class="admin-stat-card__value admin-stat-card__value--primary">{{ $stats['total_courses'] }}</p>
      </div>
      <div class="admin-stat-card__icon admin-stat-card__icon--primary">
        <i class="fas fa-book"></i>
      </div>
    </div>
    <p class="admin-stat-card__footer">
      <i class="fas fa-check-circle admin-color--success"></i> {{ $stats['published_courses'] }} đã xuất bản
    </p>
  </div>

  <div class="admin-stat-card card admin-stat-card--border-success">
    <div class="admin-stat-card__header">
      <div class="admin-stat-card__content">
        <p class="admin-stat-card__label">Tổng người dùng</p>
        <p class="admin-stat-card__value admin-stat-card__value--success">{{ $stats['total_users'] }}</p>
      </div>
      <div class="admin-stat-card__icon admin-stat-card__icon--success">
        <i class="fas fa-users"></i>
      </div>
    </div>
    <p class="admin-stat-card__footer">
      <i class="fas fa-user-graduate admin-color--info"></i> {{ $stats['total_students'] }} học viên
    </p>
  </div>

  <div class="admin-stat-card card admin-stat-card--border-blue">
    <div class="admin-stat-card__header">
      <div class="admin-stat-card__content">
        <p class="admin-stat-card__label">Tổng doanh thu</p>
        <p class="admin-stat-card__value admin-stat-card__value--blue">₫{{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
      </div>
      <div class="admin-stat-card__icon admin-stat-card__icon--blue">
        <i class="fas fa-dollar-sign"></i>
      </div>
    </div>
    <p class="admin-stat-card__footer">
      <i class="fas fa-shopping-cart admin-color--orange"></i> {{ $stats['total_orders'] }} đơn hàng
    </p>
  </div>

  <div class="admin-stat-card card admin-stat-card--border-orange">
    <div class="admin-stat-card__header">
      <div class="admin-stat-card__content">
        <p class="admin-stat-card__label">Tổng đăng ký</p>
        <p class="admin-stat-card__value admin-stat-card__value--orange">{{ $stats['total_enrollments'] }}</p>
      </div>
      <div class="admin-stat-card__icon admin-stat-card__icon--orange">
        <i class="fas fa-user-check"></i>
      </div>
    </div>
    <p class="admin-stat-card__footer">
      <i class="fas fa-star admin-color--warning-icon"></i> {{ $stats['total_reviews'] }} đánh giá
    </p>
  </div>
</div>

<!-- Charts and Tables Row -->
<div class="admin-dashboard-grid">
  <!-- Recent Orders -->
  <div class="admin-card card">
    <div class="admin-card__header">
      <h2 class="admin-card__title">Đơn hàng gần đây</h2>
      <a href="{{ route('admin.statistics.revenue') }}" class="admin-card__link">
        Xem tất cả <i class="fas fa-arrow-right" style="margin-left: var(--spacing-xs);"></i>
      </a>
    </div>
    @if($recentOrders->count() > 0)
      <div class="admin-card__list">
        @foreach($recentOrders as $order)
          <div class="admin-card__item admin-order-item">
            <div class="admin-card__item-header">
              <div class="admin-order-item__header">
                <div class="admin-order-item__code-row">
                  <span class="admin-order-item__code">#{{ $order->code }}</span>
                  @if($order->status == 'paid')
                    <span class="admin-badge admin-badge--published">Đã thanh toán</span>
                  @elseif($order->status == 'pending')
                    <span class="admin-badge admin-badge--draft">Chờ thanh toán</span>
                  @endif
                </div>
                <div class="admin-order-item__user">
                  <i class="fas fa-user" style="margin-right: var(--spacing-xs);"></i>{{ $order->user->name }}
                </div>
                <div class="admin-order-item__date">
                  <i class="far fa-clock" style="margin-right: var(--spacing-xs);"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                </div>
              </div>
              <div class="admin-order-item__value">
                <div class="admin-order-item__total">
                  ₫{{ number_format($order->total, 0, ',', '.') }}
                </div>
                <div class="admin-order-item__count">
                  {{ $order->items->count() }} khóa học
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="admin-empty-state">
        <i class="fas fa-inbox admin-empty-state__icon"></i>
        <p>Chưa có đơn hàng nào</p>
      </div>
    @endif
  </div>

  <!-- Top Courses -->
  <div class="admin-card card">
    <div class="admin-card__header">
      <h2 class="admin-card__title">Khóa học phổ biến</h2>
      <a href="{{ route('admin.courses.index') }}" class="admin-card__link">
        Xem tất cả <i class="fas fa-arrow-right" style="margin-left: var(--spacing-xs);"></i>
      </a>
    </div>
    @if($topCourses->count() > 0)
      <div class="admin-card__list">
        @foreach($topCourses as $index => $course)
          <div class="admin-card__item">
            <div class="admin-course-item">
              <div class="admin-course-rank">
                {{ $index + 1 }}
              </div>
              <div class="admin-course-item__content">
                <div class="admin-course-item__title">
                  {{ $course->title }}
                </div>
                <div class="admin-course-item__meta">
                  <span><i class="fas fa-users" style="margin-right: var(--spacing-xs);"></i>{{ $course->enrolled_students }}</span>
                  @if($course->rating > 0)
                    <span><i class="fas fa-star admin-color--warning-icon" style="margin-right: var(--spacing-xs);"></i>{{ number_format($course->rating, 1) }}</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="admin-empty-state">
        <i class="fas fa-book admin-empty-state__icon"></i>
        <p>Chưa có khóa học nào</p>
      </div>
    @endif
  </div>
</div>

<!-- Quick Actions -->
<div class="admin-actions">
  <a href="{{ route('admin.courses.create') }}" class="admin-action admin-action--primary admin-action--with-padding">
    <i class="fas fa-plus"></i>
    <span>Tạo khóa học</span>
  </a>
  <a href="{{ route('admin.categories.create') }}" class="admin-action admin-action--secondary admin-action--with-padding">
    <i class="fas fa-folder-plus"></i>
    <span>Tạo danh mục</span>
  </a>
  <a href="{{ route('admin.coupons.create') }}" class="admin-action admin-action--secondary admin-action--with-padding">
    <i class="fas fa-tag"></i>
    <span>Tạo mã giảm giá</span>
  </a>
  <a href="{{ route('admin.users.create') }}" class="admin-action admin-action--secondary admin-action--with-padding">
    <i class="fas fa-user-plus"></i>
    <span>Tạo người dùng</span>
  </a>
</div>

<!-- Statistics Links -->
<div class="admin-card card admin-statistics-card">
  <h2 class="admin-statistics-card__title">Thống kê chi tiết</h2>
  <div class="admin-actions">
    <a href="{{ route('admin.statistics.revenue') }}" class="admin-statistics-link">
      <i class="fas fa-chart-line admin-statistics-link__icon"></i>
      <div class="admin-statistics-link__text">Thống kê doanh thu</div>
    </a>
    <a href="{{ route('admin.statistics.courses') }}" class="admin-statistics-link">
      <i class="fas fa-chart-bar admin-statistics-link__icon"></i>
      <div class="admin-statistics-link__text">Thống kê khóa học</div>
    </a>
    <a href="{{ route('admin.statistics.students') }}" class="admin-statistics-link">
      <i class="fas fa-chart-pie admin-statistics-link__icon"></i>
      <div class="admin-statistics-link__text">Thống kê người học</div>
    </a>
  </div>
</div>
@endsection

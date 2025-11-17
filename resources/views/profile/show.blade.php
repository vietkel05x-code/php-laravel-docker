@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
@endpush

@section('content')
<section class="profile-page">
  <h1 class="profile-page__title">Thông tin cá nhân</h1>

  @if(session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
  @endif

  <div class="profile-grid">
    <!-- Avatar Section -->
    <div class="profile-avatar">
      <div class="profile-avatar__container">
        @if(Auth::user()->avatar)
          <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="profile-avatar__image">
        @else
          <span class="profile-avatar__initial">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
        @endif
      </div>
      <a href="{{ route('profile.edit') }}" class="profile-avatar__button">
        Chỉnh sửa thông tin
      </a>
    </div>

    <!-- Info Section -->
    <div class="profile-info card">
      <h2 class="profile-info__title">Thông tin tài khoản</h2>
      
      <div class="profile-info__list">
        <div>
          <p class="profile-info__item-label">Họ và tên</p>
          <p class="profile-info__item-value">{{ Auth::user()->name }}</p>
        </div>

        <div>
          <p class="profile-info__item-label">Email</p>
          <p class="profile-info__item-value profile-info__item-value--normal">{{ Auth::user()->email }}</p>
        </div>

        <div>
          <p class="profile-info__item-label">Vai trò</p>
          <p class="profile-info__item-value">
            <span class="profile-info__badge">{{ ucfirst(Auth::user()->role) }}</span>
          </p>
        </div>

        <div>
          <p class="profile-info__item-label">Ngày tham gia</p>
          <p class="profile-info__item-value profile-info__item-value--normal">{{ Auth::user()->created_at->format('d/m/Y') }}</p>
        </div>
      </div>

      <hr class="profile-info__divider">

      <div class="profile-info__stats">
        <div>
          <p class="profile-info__stat-value">{{ Auth::user()->enrollments()->count() }}</p>
          <p class="profile-info__stat-label">Khóa học đã đăng ký</p>
        </div>
        <div>
          <p class="profile-info__stat-value">{{ Auth::user()->orders()->count() }}</p>
          <p class="profile-info__stat-label">Đơn hàng</p>
        </div>
        <div>
          <p class="profile-info__stat-value">{{ Auth::user()->reviews()->count() }}</p>
          <p class="profile-info__stat-label">Đánh giá đã gửi</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

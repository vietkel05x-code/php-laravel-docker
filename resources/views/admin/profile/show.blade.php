@extends('layouts.admin')

@section('title', 'Thông tin cá nhân')
@section('page-title', 'Thông tin cá nhân')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<div class="admin-profile-page">
  <div class="admin-profile-grid">
    <!-- Avatar Section -->
    <div class="admin-profile-avatar-section">
      @php
        $adminUser = \App\Helpers\AdminHelper::user();
      @endphp
      <div class="admin-profile-avatar">
        @if($adminUser && $adminUser->avatar)
          <img src="{{ $adminUser->avatar_url }}" alt="Avatar">
        @else
          <span>{{ $adminUser ? strtoupper(substr($adminUser->name, 0, 1)) : 'A' }}</span>
        @endif
      </div>
      <a href="{{ route('admin.profile.edit') }}" class="btn btn--primary admin-flex admin-flex--center">
        <i class="fas fa-edit"></i>
        <span>Chỉnh sửa thông tin</span>
      </a>
    </div>

    <!-- Info Section -->
    <div class="admin-profile-info">
      <h2 class="admin-profile-info__title">Thông tin tài khoản</h2>
      
      <div class="admin-profile-info__list">
        <div class="admin-profile-info__item">
          <p class="admin-profile-info__label">Họ và tên</p>
          <p class="admin-profile-info__value">{{ $adminUser ? $adminUser->name : 'Admin' }}</p>
        </div>

        <div class="admin-profile-info__item">
          <p class="admin-profile-info__label">Email</p>
          <p class="admin-profile-info__value admin-profile-info__value--secondary">{{ $adminUser ? $adminUser->email : '' }}</p>
        </div>

        <div class="admin-profile-info__item">
          <p class="admin-profile-info__label">Vai trò</p>
          <span class="admin-profile-badge">
            <i class="fas fa-shield-alt" style="margin-right: var(--spacing-xs);"></i>
            Administrator
          </span>
        </div>

        <div class="admin-profile-info__item admin-profile-info__item--last">
          <p class="admin-profile-info__label">Ngày tham gia</p>
          <p class="admin-profile-info__value admin-profile-info__value--secondary">
            <i class="far fa-calendar" style="margin-right: var(--spacing-xs); color: #9ca3af;"></i>
            {{ $adminUser ? $adminUser->created_at->format('d/m/Y') : '' }}
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

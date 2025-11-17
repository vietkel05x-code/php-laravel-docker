@extends('layouts.admin')

@section('title', $user->id ? 'Sửa người dùng' : 'Thêm người dùng')
@section('page-title', $user->id ? 'Sửa người dùng' : 'Thêm người dùng')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-form-page admin-form-page--narrow">
  <h1 class="admin-form-page__title">{{ $user->id ? 'Sửa người dùng' : 'Thêm người dùng' }}</h1>

  @if($errors->any())
    <div class="alert alert--error">
      <ul class="admin-error-list">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ $user->id ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" class="admin-form">
    @csrf
    @if($user->id)
      @method('PUT')
    @endif

    @if($user->id)
      {{-- Khi sửa: chỉ hiển thị thông tin, không cho sửa --}}
      <div class="admin-user-info">
        <h3 class="admin-user-info__title">Thông tin người dùng</h3>
        
        <div class="admin-user-info__field">
          <label class="admin-user-info__label">Họ và tên</label>
          <div class="admin-user-info__value">{{ $user->name }}</div>
        </div>

        <div class="admin-user-info__field">
          <label class="admin-user-info__label">Email</label>
          <div class="admin-user-info__value">{{ $user->email }}</div>
        </div>

        <div class="admin-user-info__field admin-user-info__field--last">
          <label class="admin-user-info__label">Ngày tạo</label>
          <div class="admin-user-info__value">{{ $user->created_at->format('d/m/Y H:i') }}</div>
        </div>
      </div>

      <div class="admin-form__field">
        <label for="role" class="admin-form__label">Vai trò *</label>
        <select name="role" id="role" required class="admin-form__select">
          <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Học viên</option>
          <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Quản trị</option>
        </select>
        <small class="admin-help-text">Chỉ có thể thay đổi vai trò của người dùng</small>
      </div>
    @else
      {{-- Khi tạo mới: hiển thị tất cả các field --}}
      <div class="admin-form__field">
        <label for="name" class="admin-form__label">Họ và tên *</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="admin-form__input">
      </div>

      <div class="admin-form__field">
        <label for="email" class="admin-form__label">Email *</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="admin-form__input">
      </div>

      <div class="admin-form__field">
        <label for="role" class="admin-form__label">Vai trò *</label>
        <select name="role" id="role" required class="admin-form__select">
          <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Học viên</option>
          <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Quản trị</option>
        </select>
      </div>

      <div class="admin-form__field">
        <label for="password" class="admin-form__label">Mật khẩu *</label>
        <input type="password" name="password" id="password" required class="admin-form__input" minlength="8">
      </div>

      <div class="admin-form__field">
        <label for="password_confirmation" class="admin-form__label">Xác nhận mật khẩu *</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required class="admin-form__input" minlength="8">
      </div>
    @endif

    <div class="admin-form__actions">
      <a href="{{ route('admin.users.index') }}" class="btn btn--outline admin-form-actions__button">
        Hủy
      </a>
      <button type="submit" class="btn btn--primary admin-form-actions__button">
        {{ $user->id ? 'Cập nhật' : 'Tạo' }}
      </button>
    </div>
  </form>
</section>
@endsection

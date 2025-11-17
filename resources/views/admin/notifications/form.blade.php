@extends('layouts.admin')

@section('title', 'Gửi thông báo')
@section('page-title', 'Gửi thông báo')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-form-page admin-form-page--narrow">
  <h1 class="admin-form-page__title">Gửi thông báo</h1>

  @if($errors->any())
    <div class="alert alert--error">
      <ul class="admin-error-list">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.notifications.store') }}" method="POST" class="admin-form">
    @csrf

    <div class="admin-form__field">
      <label for="title" class="admin-form__label">Tiêu đề *</label>
      <input type="text" name="title" id="title" value="{{ old('title') }}" required
             class="admin-form__input" placeholder="Ví dụ: Khóa học mới đã được thêm vào">
    </div>

    <div class="admin-form__field">
      <label for="body" class="admin-form__label">Nội dung *</label>
      <textarea name="body" id="body" rows="6" required class="admin-form__textarea"
                placeholder="Nhập nội dung thông báo...">{{ old('body') }}</textarea>
    </div>

    <div class="admin-form__field">
      <label for="target" class="admin-form__label">Gửi đến *</label>
      <select name="target" id="target" required class="admin-form__select">
        <option value="all" {{ old('target') == 'all' ? 'selected' : '' }}>Tất cả người dùng ({{ $totalUsers }} học viên)</option>
        <option value="students" {{ old('target') == 'students' ? 'selected' : '' }}>Chỉ học viên</option>
        <option value="admins" {{ old('target') == 'admins' ? 'selected' : '' }}>Chỉ quản trị viên</option>
      </select>
    </div>

    <div class="admin-form__actions">
      <a href="{{ route('admin.notifications.index') }}" class="btn btn--outline admin-form-actions__button">
        Hủy
      </a>
      <button type="submit" class="btn btn--primary admin-form-actions__button">
        Gửi thông báo
      </button>
    </div>
  </form>
</section>
@endsection

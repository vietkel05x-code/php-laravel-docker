@extends('layouts.admin')

@section('title', $category->id ? 'Sửa danh mục' : 'Thêm danh mục')
@section('page-title', $category->id ? 'Sửa danh mục' : 'Thêm danh mục')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-form-page admin-form-page--narrow">
  <h1 class="admin-form-page__title">{{ $category->id ? 'Sửa danh mục' : 'Thêm danh mục' }}</h1>

  @if($errors->any())
    <div class="alert alert--error">
      <ul class="admin-error-list">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ $category->id ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" class="admin-form">
    @csrf
    @if($category->id)
      @method('PUT')
    @endif

    <div class="admin-form__field">
      <label for="name" class="admin-form__label">Tên danh mục *</label>
      <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="admin-form__input">
    </div>

    <div class="admin-form__field">
      <label for="slug" class="admin-form__label">Slug</label>
      <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" class="admin-form__input" placeholder="Tự động tạo từ tên nếu để trống">
      <small class="admin-help-text">Slug sẽ được tự động tạo từ tên nếu để trống</small>
    </div>

    <div class="admin-form__field">
      <label for="parent_id" class="admin-form__label">Danh mục cha</label>
      <select name="parent_id" id="parent_id" class="admin-form__select">
        <option value="">Không có (danh mục gốc)</option>
        @foreach($parentCategories as $parent)
          <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
            {{ $parent->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="admin-form__field">
      <label for="description" class="admin-form__label">Mô tả</label>
      <textarea name="description" id="description" rows="4" class="admin-form__textarea">{{ old('description', $category->description) }}</textarea>
    </div>

    <div class="admin-form__field">
      <label for="image" class="admin-form__label">Tên file ảnh (trong public/img/categories/)</label>
      <input type="text" name="image" id="image" value="{{ old('image', $category->image) }}" class="admin-form__input" placeholder="example.jpg">
    </div>

    <div class="admin-form__actions">
      <a href="{{ route('admin.categories.index') }}" class="btn btn--outline admin-form-actions__button">
        Hủy
      </a>
      <button type="submit" class="btn btn--primary admin-form-actions__button">
        {{ $category->id ? 'Cập nhật' : 'Tạo' }}
      </button>
    </div>
  </form>
</section>
@endsection

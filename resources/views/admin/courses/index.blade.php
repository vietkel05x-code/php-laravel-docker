@extends('layouts.admin')

@section('title', 'Danh sách khóa học')
@section('page-title', 'Quản lý khóa học')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<div class="admin-page">
  <div class="admin-page-header">
    <h2 class="admin-page-header__title">Danh sách khóa học</h2>
    <a href="{{ route('admin.courses.create') }}" class="admin-page-header__action">
      + Tạo khóa học mới
    </a>
  </div>

  <!-- Search and Filter -->
  <form method="GET" action="{{ route('admin.courses.index') }}" class="admin-filters">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm khóa học..." 
           class="admin-filters__search">
    
    <select name="category" class="admin-filters__select" onchange="this.form.submit()">
      <option value="">Tất cả danh mục</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
          {{ $cat->name }}
        </option>
      @endforeach
    </select>
    
    <select name="status" class="admin-filters__select" onchange="this.form.submit()">
      <option value="">Tất cả trạng thái</option>
      <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
      <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
      <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
      <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
    </select>
    
    <button type="submit" class="admin-filters__button">Tìm kiếm</button>
    @if(request('search') || request('status') || request('category'))
      <a href="{{ route('admin.courses.index') }}" class="admin-filters__clear">Xóa bộ lọc</a>
    @endif
  </form>

  <div class="admin-table-wrapper">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tiêu đề</th>
          <th>Danh mục</th>
          <th>Trạng thái</th>
          <th class="admin-table__cell--right">Giá</th>
          <th class="admin-table__cell--center">Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($courses as $course)
          <tr>
            <td>{{ $course->id }}</td>
            <td>
              <a href="{{ route('admin.courses.edit', $course) }}" class="admin-table__link">
                {{ $course->title }}
              </a>
              <div class="admin-table__slug">{{ $course->slug }}</div>
            </td>
            <td>
              @if($course->category)
                <span class="admin-badge admin-badge--info">{{ $course->category->name }}</span>
              @else
                <span class="admin-table__muted">Chưa phân loại</span>
              @endif
            </td>
            <td>
              <span class="admin-badge admin-badge--{{ $course->status }}">
                @if($course->status === 'published')
                  Đã xuất bản
                @elseif($course->status === 'draft')
                  Bản nháp
                @elseif($course->status === 'hidden')
                  Ẩn
                @else
                  {{ ucfirst($course->status) }}
                @endif
              </span>
            </td>
            <td class="admin-table__cell--right admin-table__cell--primary">
              ₫{{ number_format($course->price, 0, ',', '.') }}
            </td>
            <td class="admin-table__cell--center">
              <div class="admin-actions-container">
                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn--primary btn--sm">
                  Sửa
                </a>
                <a href="{{ route('courses.show', $course->slug) }}" target="_blank" class="btn btn--outline btn--sm">
                  Xem
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="admin-table__empty">
              Không tìm thấy khóa học nào
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="admin-pagination">
    {{ $courses->links('vendor.pagination.custom') }}
  </div>
</div>
@endsection

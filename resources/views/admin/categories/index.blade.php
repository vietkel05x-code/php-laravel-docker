@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-page">
  <div class="admin-page-header">
    <h1 class="admin-page-header__title">Quản lý danh mục</h1>
    <a href="{{ route('admin.categories.create') }}" class="admin-page-header__action">
      + Thêm danh mục
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert--error">{{ session('error') }}</div>
  @endif

  <div class="admin-table-wrapper">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên</th>
          <th>Slug</th>
          <th>Danh mục cha</th>
          <th class="admin-table__cell--center">Khóa học</th>
          <th class="admin-table__cell--center">Hành động</th>
        </tr>
      </thead>
      <tbody>
        @foreach($categories as $category)
          <tr>
            <td>{{ $category->id }}</td>
            <td class="admin-table__cell--bold">{{ $category->name }}</td>
            <td class="admin-table__cell--secondary">{{ $category->slug }}</td>
            <td>
              @if($category->parent)
                <span class="admin-text--secondary">{{ $category->parent->name }}</span>
              @else
                <span class="admin-text--muted">-</span>
              @endif
            </td>
            <td class="admin-table__cell--center">
              <span class="admin-badge admin-badge--info">
                {{ $category->courses()->count() }}
              </span>
            </td>
            <td class="admin-table__cell--center">
              <div class="admin-actions-container">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn--outline btn--sm">
                  Sửa
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="admin-actions-container--inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')" 
                          class="btn btn--danger btn--sm">
                    Xóa
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</section>
@endsection

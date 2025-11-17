@extends('layouts.admin')

@section('title', 'Quản lý người dùng')
@section('page-title', 'Quản lý người dùng')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-page">
  <div class="admin-page-header">
    <h1 class="admin-page-header__title">Quản lý người dùng</h1>
    <a href="{{ route('admin.users.create') }}" class="admin-page-header__action">
      + Thêm người dùng
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert--error">{{ session('error') }}</div>
  @endif

  <!-- Filters -->
  <form method="GET" action="{{ route('admin.users.index') }}" class="admin-filters card admin-mb--lg">
    <div class="admin-form__grid admin-form__grid--3cols admin-flex--center-items" style="align-items: end;">
      <div>
        <label class="admin-form__label">Tìm kiếm</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên, email..." 
               class="admin-form__input">
      </div>
      <div>
        <label class="admin-form__label">Vai trò</label>
        <select name="role" class="admin-form__select">
          <option value="">Tất cả</option>
          <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Học viên</option>
          <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị</option>
        </select>
      </div>
      <div class="admin-flex admin-flex--gap-sm">
        <button type="submit" class="btn btn--primary">Lọc</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn--outline">Reset</a>
      </div>
    </div>
  </form>

  <!-- Users Table -->
  @if($users->count() > 0)
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Ngày tạo</th>
            <th class="admin-table__cell--center">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td class="admin-table__cell--bold">{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>
                <span class="admin-badge" style="background: {{ $user->role == 'admin' ? 'var(--color-primary)' : 'var(--color-success)' }}; color: white;">
                  {{ ucfirst($user->role) }}
                </span>
              </td>
              <td class="admin-table__cell--secondary">
                {{ $user->created_at->format('d/m/Y') }}
              </td>
              <td class="admin-table__cell--center">
                <div class="admin-actions-container">
                  <a href="{{ route('admin.users.edit', $user) }}" class="btn btn--outline btn--sm">
                    Sửa
                  </a>
                  @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="admin-actions-container--inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')" 
                              class="btn btn--danger btn--sm">
                        Xóa
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="admin-pagination">
      {{ $users->links() }}
    </div>
  @else
    <div class="admin-card card">
      <p class="admin-empty-state">
        Không tìm thấy người dùng nào
      </p>
    </div>
  @endif
</section>
@endsection

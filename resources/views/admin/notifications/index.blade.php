@extends('layouts.admin')

@section('title', 'Quản lý thông báo')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-page">
  <div class="admin-page-header">
    <h1 class="admin-page-header__title">Quản lý thông báo</h1>
    <a href="{{ route('admin.notifications.create') }}" class="admin-page-header__action">
      + Gửi thông báo mới
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
  @endif

  @if($notifications->count() > 0)
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Tiêu đề</th>
            <th>Người gửi</th>
            <th class="admin-table__cell--center">Người nhận</th>
            <th>Ngày gửi</th>
            <th class="admin-table__cell--center">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @foreach($notifications as $notification)
            <tr>
              <td class="admin-table__cell--bold">{{ $notification->title }}</td>
              <td>{{ $notification->creator->name ?? 'System' }}</td>
              <td class="admin-table__cell--center">
                <span class="admin-badge admin-badge--info">
                  {{ $notification->users->count() }} người
                </span>
              </td>
              <td class="admin-table__cell--secondary">
                {{ $notification->created_at->format('d/m/Y H:i') }}
              </td>
              <td class="admin-table__cell--center">
                <div class="admin-actions-container">
                  <a href="{{ route('admin.notifications.show', $notification) }}" class="btn btn--outline btn--sm">
                    Xem
                  </a>
                  <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" class="admin-actions-container--inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa thông báo này?')" 
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

    <div class="admin-pagination">
      {{ $notifications->links() }}
    </div>
  @else
    <div class="admin-card card">
      <p class="admin-empty-state admin-empty-state--with-margin">
        Chưa có thông báo nào
      </p>
      <div class="admin-flex admin-flex--center">
        <a href="{{ route('admin.notifications.create') }}" class="btn btn--primary">
          Gửi thông báo đầu tiên
        </a>
      </div>
    </div>
  @endif
</section>
@endsection

@extends('layouts.admin')

@section('title', 'Chi tiết thông báo')
@section('page-title', 'Chi tiết thông báo')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-page admin-max-w--900">
  <div class="admin-mb--lg">
    <a href="{{ route('admin.notifications.index') }}" class="admin-card__link">← Quay lại danh sách</a>
  </div>

  <div class="admin-card">
    <div class="admin-notification-header">
      <div>
        <h1 class="admin-notification-title">{{ $notification->title }}</h1>
        <p class="admin-notification-meta">
          Gửi bởi: {{ $notification->creator->name ?? 'System' }} · 
          {{ $notification->created_at->format('d/m/Y H:i') }}
        </p>
      </div>
      <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" class="admin-actions-container--inline">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa thông báo này?')" 
                class="btn btn--danger">
          Xóa
        </button>
      </form>
    </div>

    <div class="admin-notification-body">
      <p class="admin-notification-body__text">{{ $notification->body }}</p>
    </div>

    <div>
      <h3 class="admin-card__title" style="margin-bottom: var(--spacing-md);">Người nhận ({{ $notification->users->count() }})</h3>
      <div class="admin-notification-recipients">
        @foreach($notification->users as $user)
          <div class="admin-notification-recipient">
            <p class="admin-notification-recipient__name">{{ $user->name }}</p>
            <p class="admin-notification-recipient__email">{{ $user->email }}</p>
            @if($user->pivot->read_at)
              <span class="admin-badge admin-badge--success admin-notification-recipient__badge">
                Đã đọc
              </span>
            @else
              <span class="admin-badge admin-badge--gray admin-notification-recipient__badge">
                Chưa đọc
              </span>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection

@extends('layouts.app')

@section('title', 'Thông báo')

@section('content')
<section class="page-wrapper" style="max-width:900px;margin:40px auto;padding:0 20px">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
    <h1>Thông báo của tôi</h1>
    @if(Auth::user()->unreadNotificationsCount() > 0)
      <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display:inline">
        @csrf
        <button type="submit" style="padding:10px 20px;background:#28a745;color:white;border:none;border-radius:8px;cursor:pointer;font-weight:bold">
          Đánh dấu tất cả đã đọc
        </button>
      </form>
    @endif
  </div>

  @if(session('success'))
    <div style="background:#d4edda;color:#155724;padding:12px;border-radius:8px;margin-bottom:20px">
      {{ session('success') }}
    </div>
  @endif

  @if($notifications->count() > 0)
    <div style="display:grid;gap:16px">
      @foreach($notifications as $notification)
        @php
          $isRead = $notification->pivot->read_at !== null;
        @endphp
        <div style="border:1px solid #eee;border-radius:12px;padding:20px;background:#fff;{{ !$isRead ? 'border-left:4px solid #a435f0;' : '' }}">
          <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px">
            <div style="flex:1">
              <h3 style="margin:0 0 8px 0;font-size:18px;{{ !$isRead ? 'font-weight:bold;' : '' }}">
                {{ $notification->title }}
              </h3>
              <p style="color:#666;margin:0;font-size:14px">
                {{ $notification->created_at->format('d/m/Y H:i') }}
                @if($notification->creator)
                  · Gửi bởi {{ $notification->creator->name }}
                @endif
              </p>
            </div>
            @if(!$isRead)
              <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" style="padding:6px 12px;background:#a435f0;color:white;border:none;border-radius:6px;cursor:pointer;font-size:12px">
                  Đánh dấu đã đọc
                </button>
              </form>
            @else
              <span style="padding:6px 12px;background:#d4edda;color:#155724;border-radius:6px;font-size:12px;font-weight:bold">
                ✓ Đã đọc
              </span>
            @endif
          </div>
          <div style="padding:12px;background:#f8f9fa;border-radius:8px">
            <p style="margin:0;line-height:1.6;white-space:pre-wrap">{{ $notification->body }}</p>
          </div>
        </div>
      @endforeach
    </div>

    <div style="margin-top:32px">
      {{ $notifications->links() }}
    </div>
  @else
    <div style="text-align:center;padding:60px 20px;background:#fff;border:1px solid #eee;border-radius:12px">
      <p style="font-size:18px;color:#666;margin-bottom:24px">Bạn chưa có thông báo nào</p>
    </div>
  @endif
</section>
@endsection


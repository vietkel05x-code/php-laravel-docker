@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
@endpush

@section('content')
<section class="order-detail-page">
  <div>
    <a href="{{ route('orders.index') }}" class="order-detail__back-link">← Quay lại danh sách đơn hàng</a>
  </div>

  <h1 class="order-detail__title">Đơn hàng #{{ $order->code }}</h1>

  @if(session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
  @endif

  <div class="order-detail__content">
    <!-- Order Info -->
    <div class="order-detail__card">
      <div class="order-detail__info-grid">
        <div>
          <p class="order-detail__info-label">Ngày đặt hàng</p>
          <p class="order-detail__info-value">{{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div>
          <p class="order-detail__info-label">Trạng thái</p>
          <span class="order-detail__status-badge order-detail__status-badge--{{ $order->status }}">
            @if($order->status === 'paid')
              Đã thanh toán
            @elseif($order->status === 'pending')
              Chờ thanh toán
            @elseif($order->status === 'failed')
              Thanh toán thất bại
            @else
              {{ ucfirst($order->status) }}
            @endif
          </span>
        </div>
      </div>

      <hr class="order-detail__divider">

      <h3 class="order-detail__items-title">Khóa học đã mua</h3>
      <div class="order-detail__items-list">
        @foreach($order->items as $item)
          <div class="order-detail__item">
            <img src="{{ $item->course->thumbnail_url }}" alt="{{ $item->course->title }}" 
                 class="order-detail__item-image">
            <div class="order-detail__item-content">
              <h4 class="order-detail__item-title">
                <a href="{{ route('courses.show', $item->course->slug) }}" class="order-detail__item-title-link">
                  {{ $item->course->title }}
                </a>
              </h4>
              <p class="order-detail__item-instructor">{{ $item->course->instructor->name ?? 'Instructor' }}</p>
              <div class="order-detail__item-footer">
                <span class="order-detail__item-price">
                  ₫{{ number_format($item->price, 0, ',', '.') }}
                </span>
                @if($order->status === 'paid')
                  <a href="{{ route('student.learn', $item->course->slug) }}" class="btn btn--success btn--sm">
                    Bắt đầu học
                  </a>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Payment Summary -->
    <div class="order-detail__card">
      <h3 class="order-detail__summary-title">Tóm tắt thanh toán</h3>
      
      <div class="order-detail__summary-row">
        <span>Tạm tính:</span>
        <span>₫{{ number_format($order->subtotal, 0, ',', '.') }}</span>
      </div>
      
      @if($order->discount > 0)
        <div class="order-detail__summary-row order-detail__summary-row--discount">
          <span>Giảm giá:</span>
          <span>-₫{{ number_format($order->discount, 0, ',', '.') }}</span>
        </div>
      @endif

      <hr class="order-detail__divider">

      <div class="order-detail__summary-row order-detail__summary-row--total">
        <span>Tổng cộng:</span>
        <span class="order-detail__summary-total-amount">₫{{ number_format($order->total, 0, ',', '.') }}</span>
      </div>

      @if($order->status === 'pending')
        <div class="order-detail__payment-warning">
          <p class="order-detail__payment-warning-title">Đơn hàng chưa được thanh toán</p>
          <div class="order-detail__payment-buttons">
            <a href="{{ route('payment.gateway', ['order' => $order->id, 'method' => 'momo']) }}" 
               class="order-detail__payment-button order-detail__payment-button--momo">
              <i class="fas fa-wallet"></i> Thanh toán MoMo
            </a>
            <a href="{{ route('payment.gateway', ['order' => $order->id, 'method' => 'vnpay']) }}" 
               class="order-detail__payment-button order-detail__payment-button--vnpay">
              <i class="fas fa-credit-card"></i> Thanh toán VNPay
            </a>
            <a href="{{ route('payment.bank-transfer', $order) }}" 
               class="order-detail__payment-button order-detail__payment-button--bank">
              <i class="fas fa-university"></i> Chuyển khoản
            </a>
          </div>
        </div>
      @endif

      @if($order->payment)
        <div class="order-detail__payment-info">
          <h4 class="order-detail__payment-info-title">Thông tin thanh toán</h4>
          <div class="order-detail__payment-info-grid">
            <div class="order-detail__payment-info-row">
              <span class="order-detail__payment-info-label">Phương thức:</span>
              <span class="order-detail__payment-info-value">
                @if($order->payment->provider === 'momo') Ví MoMo
                @elseif($order->payment->provider === 'vnpay') VNPay
                @elseif($order->payment->provider === 'bank_transfer') Chuyển khoản
                @else {{ $order->payment->provider }}
                @endif
              </span>
            </div>
            <div class="order-detail__payment-info-row">
              <span class="order-detail__payment-info-label">Mã giao dịch:</span>
              <span class="order-detail__payment-info-value order-detail__payment-info-value--mono">{{ $order->payment->transaction_id }}</span>
            </div>
            <div class="order-detail__payment-info-row">
              <span class="order-detail__payment-info-label">Trạng thái:</span>
              <span class="order-detail__payment-status-badge order-detail__payment-status-badge--{{ $order->payment->status }}">
                @if($order->payment->status === 'succeeded')
                  Thành công
                @elseif($order->payment->status === 'failed')
                  Thất bại
                @else
                  Đang xử lý
                @endif
              </span>
            </div>
            <div class="order-detail__payment-info-row">
              <span class="order-detail__payment-info-label">Thời gian:</span>
              <span>{{ $order->payment->created_at->format('d/m/Y H:i') }}</span>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
</section>
@endsection

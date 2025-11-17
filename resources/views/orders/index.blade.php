@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
@endpush

@section('content')
<section class="orders-page">
  <h1 class="orders-page__title">Đơn hàng của tôi</h1>

  @if($orders->count() > 0)
    <div class="orders-list">
      @foreach($orders as $order)
        <div class="order-card">
          <div class="order-card__header">
            <div class="order-card__info">
              <h3 class="order-card__title">
                <a href="{{ route('orders.show', $order) }}" class="order-card__title-link">
                  Đơn hàng #{{ $order->code }}
                </a>
              </h3>
              <p class="order-card__date">
                {{ $order->created_at->format('d/m/Y H:i') }}
              </p>
            </div>
            <div class="order-card__status">
              <span class="order-card__status-badge order-card__status-badge--{{ $order->status }}">
                {{ ucfirst($order->status) }}
              </span>
              <p class="order-card__total">
                ₫{{ number_format($order->total, 0, ',', '.') }}
              </p>
            </div>
          </div>

          <a href="{{ route('orders.show', $order) }}" class="order-card__link">
            Xem chi tiết →
          </a>
        </div>
      @endforeach
    </div>

    <div style="margin-top: var(--spacing-xl);">
      {{ $orders->links() }}
    </div>
  @else
    <div class="orders-empty">
      <p class="orders-empty__message">Bạn chưa có đơn hàng nào</p>
      <a href="{{ route('home') }}" class="btn btn--primary">
        Khám phá khóa học
      </a>
    </div>
  @endif
</section>
@endsection

@extends('layouts.app')

@section('title', 'Giỏ hàng')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/cart.css') }}">
@endpush

@section('content')
<section class="cart-page">
  <h1 class="cart-page__title">Giỏ hàng của tôi</h1>

  @if(session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert--error">{{ session('error') }}</div>
  @endif

  @if(count($courses) > 0)
    <div class="cart-list">
      @foreach($courses as $course)
        <div class="cart-item">
          <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" 
               class="cart-item__image">
          
          <div class="cart-item__content">
            <h3 class="cart-item__title">
              <a href="{{ route('courses.show', $course->slug) }}" class="cart-item__title-link">
                {{ $course->title }}
              </a>
            </h3>
            <p class="cart-item__instructor">{{ $course->instructor->name ?? 'Instructor' }}</p>
            <div class="cart-item__price">
              <span class="cart-item__price-current">
                ₫{{ number_format($course->price, 0, ',', '.') }}
              </span>
              @if($course->compare_at_price)
                <span class="cart-item__price-old">
                  ₫{{ number_format($course->compare_at_price, 0, ',', '.') }}
                </span>
              @endif
            </div>
          </div>

          <form action="{{ route('cart.remove', $course->id) }}" method="POST" style="margin:0">
            @csrf
            @method('DELETE')
            <button type="submit" class="cart-item__remove">
              Xóa
            </button>
          </form>
        </div>
      @endforeach
    </div>

    <div class="cart-summary">
      <div class="cart-summary__total">
        <strong>Tổng cộng:</strong>
        <strong class="cart-summary__total-amount">₫{{ number_format($total, 0, ',', '.') }}</strong>
      </div>
      
      <div class="cart-summary__actions">
        <a href="{{ route('home') }}" class="cart-summary__continue">
          Tiếp tục mua sắm
        </a>
        @auth
          <a href="{{ route('checkout.index') }}" class="cart-summary__checkout">
            Thanh toán
          </a>
        @else
          <a href="{{ route('login') }}" class="cart-summary__checkout">
            Đăng nhập để thanh toán
          </a>
        @endauth
      </div>
    </div>
  @else
    <div class="cart-empty">
      <p class="cart-empty__message">Giỏ hàng của bạn đang trống</p>
      <a href="{{ route('home') }}" class="btn btn--primary">
        Khám phá khóa học
      </a>
    </div>
  @endif
</section>
@endsection

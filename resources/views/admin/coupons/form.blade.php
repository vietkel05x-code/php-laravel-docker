@extends('layouts.admin')

@section('title', $coupon->id ? 'Sửa mã giảm giá' : 'Thêm mã giảm giá')
@section('page-title', $coupon->id ? 'Sửa mã giảm giá' : 'Thêm mã giảm giá')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-form-page admin-form-page--narrow">
  <h1 class="admin-form-page__title">{{ $coupon->id ? 'Sửa mã giảm giá' : 'Thêm mã giảm giá' }}</h1>

  @if($errors->any())
    <div class="alert alert--error">
      <ul class="admin-error-list">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ $coupon->id ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST" class="admin-form">
    @csrf
    @if($coupon->id)
      @method('PUT')
    @endif

    <div class="admin-form__field">
      <label for="code" class="admin-form__label">Mã giảm giá *</label>
      <input type="text" name="code" id="code" value="{{ old('code', $coupon->code) }}" required
             class="admin-form__input admin-form-input--uppercase" placeholder="WINTER2025">
      <small class="admin-help-text">Mã sẽ được chuyển thành chữ hoa</small>
    </div>

    <div class="admin-form__field">
      <div class="admin-form__grid admin-form__grid--2">
        <div>
          <label for="type" class="admin-form__label">Loại *</label>
          <select name="type" id="type" required class="admin-form__select">
            <option value="percent" {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
            <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (₫)</option>
          </select>
        </div>
        <div>
          <label for="value" class="admin-form__label">Giá trị *</label>
          <input type="number" name="value" id="value" value="{{ old('value', $coupon->value) }}" required min="0" step="0.01"
                 class="admin-form__input" placeholder="{{ old('type', $coupon->type) == 'percent' ? '10' : '100000' }}">
        </div>
      </div>
    </div>

    <div class="admin-form__field">
      <div class="admin-form__grid admin-form__grid--2">
        <div>
          <label for="starts_at" class="admin-form__label">Bắt đầu từ</label>
          <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}" class="admin-form__input">
          <small class="admin-help-text">Để trống nếu không giới hạn</small>
        </div>
        <div>
          <label for="ends_at" class="admin-form__label">Kết thúc vào</label>
          <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $coupon->ends_at ? $coupon->ends_at->format('Y-m-d\TH:i') : '') }}" class="admin-form__input">
          <small class="admin-help-text">Để trống nếu không giới hạn</small>
        </div>
      </div>
    </div>

    <div class="admin-form__field">
      <label for="max_uses" class="admin-form__label">Giới hạn số lần sử dụng</label>
      <input type="number" name="max_uses" id="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" min="1"
             class="admin-form__input" placeholder="Để trống nếu không giới hạn">
      <small class="admin-help-text">Để trống nếu không giới hạn số lần sử dụng</small>
    </div>

    <div class="admin-form__actions">
      <a href="{{ route('admin.coupons.index') }}" class="btn btn--outline admin-form-actions__button">
        Hủy
      </a>
      <button type="submit" class="btn btn--primary admin-form-actions__button">
        {{ $coupon->id ? 'Cập nhật' : 'Tạo' }}
      </button>
    </div>
  </form>
</section>

<script>
document.getElementById('code').addEventListener('input', function(e) {
  e.target.value = e.target.value.toUpperCase();
});
</script>
@endsection

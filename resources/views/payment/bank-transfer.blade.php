@extends('layouts.app')

@section('title', 'Chuyển khoản ngân hàng')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/payment.css') }}">
@endpush

@section('content')
<section class="bank-transfer-page">
  <h1 class="bank-transfer-page__title">Chuyển khoản ngân hàng</h1>

  <div class="bank-transfer-grid">
    <!-- Bank Info -->
    <div>
      <div class="bank-info__card card">
        <h2 class="bank-info__title">Thông tin tài khoản</h2>
        
        <div class="bank-info__list">
          <div class="bank-info__item">
            <p class="bank-info__item-label">Ngân hàng</p>
            <p class="bank-info__item-value">Vietcombank</p>
          </div>

          <div class="bank-info__item">
            <p class="bank-info__item-label">Số tài khoản</p>
            <p class="bank-info__item-value bank-info__item-value--mono">1234567890</p>
            <button onclick="copyToClipboard('1234567890')" class="bank-info__copy-button">
              <i class="fas fa-copy"></i> Sao chép
            </button>
          </div>

          <div class="bank-info__item">
            <p class="bank-info__item-label">Chủ tài khoản</p>
            <p class="bank-info__item-value">E-LEARNING COMPANY</p>
          </div>

          <div class="bank-info__item">
            <p class="bank-info__item-label">Số tiền</p>
            <p class="bank-info__item-value bank-info__item-value--price">₫{{ number_format($order->total, 0, ',', '.') }}</p>
          </div>

          <div class="bank-info__item">
            <p class="bank-info__item-label">Nội dung chuyển khoản</p>
            <p class="bank-info__item-value bank-info__item-value--mono">#{{ $order->code }}</p>
            <button onclick="copyToClipboard('#{{ $order->code }}')" class="bank-info__copy-button">
              <i class="fas fa-copy"></i> Sao chép
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Summary & Confirmation -->
    <div>
      <div class="order-summary__card card">
        <h2 class="order-summary__title">Tóm tắt đơn hàng</h2>
        
        <div class="order-summary__item">
          <p class="order-summary__item-label">Mã đơn hàng</p>
          <p class="order-summary__item-value">#{{ $order->code }}</p>
        </div>

        <div class="order-summary__item">
          <p class="order-summary__item-label">Tổng tiền</p>
          <p class="order-summary__item-value order-summary__item-value--price">₫{{ number_format($order->total, 0, ',', '.') }}</p>
        </div>

        <hr class="order-summary__divider">

        <div class="order-summary__note">
          <p class="order-summary__note-text">
            <i class="fas fa-info-circle"></i> 
            <strong>Lưu ý:</strong> Vui lòng chuyển khoản đúng số tiền và nội dung để đơn hàng được xử lý nhanh chóng.
          </p>
        </div>
      </div>

      <!-- Confirmation Form -->
      <div class="confirmation-form__card card">
        <h3 class="confirmation-form__title">Xác nhận đã chuyển khoản</h3>
        
        <form action="{{ route('payment.confirm-bank-transfer', $order) }}" method="POST">
          @csrf

          <div class="confirmation-form__field">
            <label class="confirmation-form__label">Ngân hàng bạn chuyển từ *</label>
            <input type="text" name="bank_name" required
                   class="confirmation-form__input"
                   placeholder="Ví dụ: Vietcombank, Techcombank...">
          </div>

          <div class="confirmation-form__field">
            <label class="confirmation-form__label">Mã giao dịch *</label>
            <input type="text" name="transaction_code" required
                   class="confirmation-form__input"
                   placeholder="Mã giao dịch từ ngân hàng">
          </div>

          <div class="confirmation-form__field">
            <label class="confirmation-form__label">Ghi chú (tùy chọn)</label>
            <textarea name="note" rows="3"
                      class="confirmation-form__textarea"
                      placeholder="Thông tin bổ sung..."></textarea>
          </div>

          <button type="submit" class="confirmation-form__submit">
            <i class="fas fa-check-circle"></i> Xác nhận đã chuyển khoản
          </button>
        </form>

        <a href="{{ route('orders.show', $order) }}" class="confirmation-form__back-link">
          Quay lại đơn hàng
        </a>
      </div>
    </div>
  </div>
</section>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Đã sao chép: ' + text);
    });
}
</script>
@endsection

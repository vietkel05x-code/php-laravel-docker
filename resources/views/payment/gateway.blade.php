<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán {{ $method === 'momo' ? 'MoMo' : 'VNPay' }} - E-Learning</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
        }

        .payment-header {
            padding: 32px;
            text-align: center;
            background: linear-gradient(135deg, 
                {{ $method === 'momo' ? '#a50064 0%, #d100a5 100%' : '#134ea2 0%, #0d6efd 100%' }});
            color: white;
        }

        .payment-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 40px;
            backdrop-filter: blur(10px);
        }

        .payment-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .payment-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .payment-body {
            padding: 32px;
        }

        .order-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            font-weight: 700;
            font-size: 18px;
        }

        .info-label {
            color: #6b7280;
        }

        .info-value {
            color: #1e293b;
            font-weight: 600;
        }

        .amount-highlight {
            color: {{ $method === 'momo' ? '#a50064' : '#134ea2' }};
            font-size: 24px;
        }

        .payment-actions {
            display: grid;
            gap: 12px;
        }

        .btn {
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, 
                {{ $method === 'momo' ? '#a50064 0%, #d100a5 100%' : '#134ea2 0%, #0d6efd 100%' }});
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f4f6;
            border-top-color: {{ $method === 'momo' ? '#a50064' : '#134ea2' }};
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .note {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            color: #92400e;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <div class="payment-logo">
                @if($method === 'momo')
                    <i class="fas fa-wallet" style="color: white;"></i>
                @else
                    <i class="fas fa-credit-card" style="color: white;"></i>
                @endif
            </div>
            <h1 class="payment-title">{{ $method === 'momo' ? 'Ví MoMo' : 'VNPay' }}</h1>
            <p class="payment-subtitle">Thanh toán đơn hàng #{{ $order->code }}</p>
        </div>

        <div class="payment-body">
            <div class="order-info">
                <div class="info-row">
                    <span class="info-label">Mã đơn hàng:</span>
                    <span class="info-value">#{{ $order->code }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tạm tính:</span>
                    <span class="info-value">₫{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->discount > 0)
                <div class="info-row">
                    <span class="info-label">Giảm giá:</span>
                    <span class="info-value" style="color: #28a745;">-₫{{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Tổng thanh toán:</span>
                    <span class="info-value amount-highlight">₫{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="payment-actions">
                <form action="{{ route('payment.success', $order) }}" method="POST" id="paymentForm">
                    @csrf
                    <input type="hidden" name="method" value="{{ $method }}">
                    <button type="submit" class="btn btn-primary" id="payButton">
                        <i class="fas fa-check-circle"></i>
                        <span>Xác nhận thanh toán</span>
                    </button>
                </form>

                <a href="{{ route('payment.cancel', $order->id) }}?method={{ $method }}" 
                   class="btn btn-secondary" onclick="return confirm('Bạn có chắc muốn hủy thanh toán?')">
                    <i class="fas fa-times"></i>
                    <span>Hủy thanh toán</span>
                </a>
            </div>

            <div class="note">
                <i class="fas fa-info-circle"></i>
                <strong>Lưu ý:</strong> Đây là hệ thống thanh toán giả lập. Trong môi trường thực tế, bạn sẽ được chuyển hướng đến trang thanh toán của {{ $method === 'momo' ? 'MoMo' : 'VNPay' }}.
            </div>
        </div>
    </div>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const button = document.getElementById('payButton');
            button.disabled = true;
            button.innerHTML = '<div class="spinner"></div><span>Đang xử lý...</span>';
        });
    </script>
</body>
</html>


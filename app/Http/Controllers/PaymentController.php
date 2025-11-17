<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Services\MomoPaymentService;
use App\Services\VnpayPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Hiển thị trang thanh toán gateway (MoMo/VNPay)
     */
    public function gateway(Request $request, Order $order, $method)
    {
        // Kiểm tra order thuộc về user hiện tại
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        // Kiểm tra order chưa được thanh toán
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Đơn hàng này đã được xử lý.');
        }

        // Kiểm tra phương thức thanh toán hợp lệ
        if (!in_array($method, ['momo', 'vnpay'])) {
            return redirect()->route('checkout.index')
                ->with('error', 'Phương thức thanh toán không hợp lệ.');
        }

        // Thử tạo payment URL thật
        $paymentUrl = null;
        $useRealGateway = false;

        if ($method === 'momo') {
            $momoService = new \App\Services\MomoPaymentService();
            $paymentUrl = $momoService->createPayment(
                $order->id,
                $order->total,
                "Thanh toan don hang #{$order->code}"
            );
            $useRealGateway = !empty($paymentUrl);
            
            // Log để debug
            \Illuminate\Support\Facades\Log::info('MoMo Payment Attempt', [
                'order_id' => $order->id,
                'has_payment_url' => !empty($paymentUrl),
                'payment_url' => $paymentUrl,
                'config_check' => [
                    'partner_code' => config('services.momo.partner_code'),
                    'has_access_key' => !empty(config('services.momo.access_key')),
                    'has_secret_key' => !empty(config('services.momo.secret_key')),
                ],
            ]);
        } elseif ($method === 'vnpay') {
            $vnpayService = new \App\Services\VnpayPaymentService();
            $paymentUrl = $vnpayService->createPayment(
                $order->id,
                $order->total,
                "Thanh toan don hang #{$order->code}"
            );
            $useRealGateway = !empty($paymentUrl);
            
            // Log để debug
            \Illuminate\Support\Facades\Log::info('VNPay Payment Attempt', [
                'order_id' => $order->id,
                'has_payment_url' => !empty($paymentUrl),
                'payment_url' => $paymentUrl,
            ]);
        }

        // Nếu có payment URL thật, redirect đến MoMo/VNPay
        if ($useRealGateway && $paymentUrl) {
            return redirect($paymentUrl);
        }

        // Nếu không có API keys hoặc lỗi, hiển thị trang giả lập
        return view('payment.gateway', compact('order', 'method'));
    }

    /**
     * Xử lý thanh toán thành công (giả lập)
     */
    public function success(Request $request, Order $order)
    {
        // Kiểm tra order thuộc về user hiện tại
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        // Kiểm tra order chưa được thanh toán
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Đơn hàng này đã được xử lý.');
        }

        DB::beginTransaction();
        try {
            // Update order status
            $order->update(['status' => 'paid']);

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'provider' => $request->get('method', 'momo'),
                'transaction_id' => 'TXN' . time() . rand(1000, 9999),
                'amount' => $order->total,
                'status' => 'succeeded',
                'meta' => [
                    'payment_time' => now()->toDateTimeString(),
                    'method' => $request->get('method', 'momo'),
                ],
            ]);

            // Create enrollments
            foreach ($order->items as $item) {
                // Check if already enrolled
                $existingEnrollment = Enrollment::where('user_id', Auth::id())
                    ->where('course_id', $item->course_id)
                    ->where('status', 'active')
                    ->first();

                if (!$existingEnrollment) {
                    Enrollment::create([
                        'user_id' => Auth::id(),
                        'course_id' => $item->course_id,
                        'status' => 'active',
                        'enrolled_at' => now(),
                    ]);

                    // Update course enrolled count
                    $item->course->increment('enrolled_students');
                }
            }

            DB::commit();

            // Clear cart and coupon
            session()->forget('cart');
            session()->forget('coupon_code');

            return redirect()->route('orders.show', $order)
                ->with('success', 'Thanh toán thành công! Bạn đã được đăng ký vào các khóa học.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('orders.show', $order)
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý thanh toán thất bại (giả lập)
     */
    public function cancel(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Kiểm tra order thuộc về user hiện tại
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        // Create payment record with failed status
        Payment::create([
            'order_id' => $order->id,
            'provider' => $request->get('method', 'momo'),
            'transaction_id' => 'TXN' . time() . rand(1000, 9999),
            'amount' => $order->total,
            'status' => 'failed',
            'meta' => [
                'payment_time' => now()->toDateTimeString(),
                'method' => $request->get('method', 'momo'),
                'reason' => 'User cancelled',
            ],
        ]);

        // Update order status
        $order->update(['status' => 'failed']);

        return redirect()->route('orders.show', $order)
            ->with('error', 'Thanh toán đã bị hủy. Vui lòng thử lại.');
    }

    /**
     * Hiển thị hướng dẫn chuyển khoản ngân hàng
     */
    public function bankTransfer(Order $order)
    {
        // Kiểm tra order thuộc về user hiện tại
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        // Kiểm tra order chưa được thanh toán
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Đơn hàng này đã được xử lý.');
        }

        return view('payment.bank-transfer', compact('order'));
    }

    /**
     * Xác nhận đã chuyển khoản (giả lập - admin sẽ xác nhận thủ công)
     */
    public function confirmBankTransfer(Request $request, Order $order)
    {
        // Kiểm tra order thuộc về user hiện tại
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        // Kiểm tra order chưa được thanh toán
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Đơn hàng này đã được xử lý.');
        }

        DB::beginTransaction();
        try {
            // Update order status to pending (waiting for admin confirmation)
            $order->update(['status' => 'pending']);

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'provider' => 'bank_transfer',
                'transaction_id' => 'BANK' . time() . rand(1000, 9999),
                'amount' => $order->total,
                'status' => 'initiated',
                'meta' => [
                    'payment_time' => now()->toDateTimeString(),
                    'method' => 'bank_transfer',
                    'bank_name' => $request->get('bank_name', ''),
                    'transaction_code' => $request->get('transaction_code', ''),
                    'note' => $request->get('note', ''),
                ],
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Đã gửi thông tin chuyển khoản. Đơn hàng của bạn sẽ được xử lý sau khi chúng tôi xác nhận thanh toán.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('orders.show', $order)
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}


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
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    /**
     * Xử lý callback từ MoMo
     */
    public function momoReturn(Request $request)
    {
        $orderId = $request->get('order');
        
        // Extract order ID từ MoMo orderId (format: orderId_timestamp)
        if ($orderId && strpos($orderId, '_') !== false) {
            $orderId = explode('_', $orderId)[0];
        }
        
        $order = Order::find($orderId);
        
        if (!$order || $order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Đơn hàng không tồn tại.');
        }

        // Kiểm tra payment result từ MoMo
        $resultCode = $request->get('resultCode');
        $amount = $request->get('amount');
        $transactionId = $request->get('transId', $request->get('orderId', ''));

        if ($resultCode == 0 && $amount == $order->total) {
            // Thanh toán thành công
            return $this->processSuccessfulPayment($order, 'momo', $transactionId, $request->all());
        } else {
            // Thanh toán thất bại
            return $this->processFailedPayment($order, 'momo', $transactionId, $request->all());
        }
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ MoMo
     */
    public function momoNotify(Request $request)
    {
        // Nếu là GET request (test từ browser), hiển thị thông tin
        if ($request->isMethod('GET')) {
            return response()->json([
                'message' => 'MoMo IPN endpoint',
                'note' => 'This endpoint should be called by MoMo server via POST',
                'received_data' => $request->all(),
            ], 200);
        }

        $momoService = new MomoPaymentService();
        
        // Verify signature
        if (!$momoService->verifyCallback($request->all())) {
            Log::warning('MoMo IPN: Invalid signature', $request->all());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $orderId = $request->get('orderId');
        if ($orderId && strpos($orderId, '_') !== false) {
            $orderId = explode('_', $orderId)[0];
        }
        
        $order = Order::find($orderId);
        
        if (!$order) {
            Log::warning('MoMo IPN: Order not found', ['orderId' => $orderId, 'request' => $request->all()]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        $resultCode = $request->get('resultCode');
        $amount = $request->get('amount');
        $transactionId = $request->get('transId', $request->get('orderId', ''));

        Log::info('MoMo IPN received', [
            'order_id' => $order->id,
            'resultCode' => $resultCode,
            'amount' => $amount,
            'transactionId' => $transactionId,
        ]);

        if ($resultCode == 0 && $amount == $order->total && $order->status === 'pending') {
            DB::beginTransaction();
            try {
                $this->completePayment($order, 'momo', $transactionId, $request->all());
                DB::commit();
                Log::info('MoMo IPN: Payment completed successfully', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('MoMo IPN: Payment processing error', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json(['error' => 'Payment processing failed'], 500);
            }
        } else {
            Log::warning('MoMo IPN: Payment conditions not met', [
                'order_id' => $order->id,
                'resultCode' => $resultCode,
                'amount' => $amount,
                'order_total' => $order->total,
                'order_status' => $order->status,
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Xử lý callback từ VNPay
     */
    public function vnpayReturn(Request $request)
    {
        $orderId = $request->get('order');
        $order = Order::find($orderId);
        
        if (!$order || $order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Đơn hàng không tồn tại.');
        }

        $vnpayService = new VnpayPaymentService();
        
        // Verify signature
        if (!$vnpayService->verifyCallback($request->all())) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Xác thực thanh toán thất bại.');
        }

        $vnp_ResponseCode = $request->get('vnp_ResponseCode');
        $vnp_Amount = $request->get('vnp_Amount') / 100; // VNPay trả về số tiền nhân 100
        $vnp_TransactionNo = $request->get('vnp_TransactionNo', '');

        if ($vnp_ResponseCode == '00' && $vnp_Amount == $order->total) {
            // Thanh toán thành công
            return $this->processSuccessfulPayment($order, 'vnpay', $vnp_TransactionNo, $request->all());
        } else {
            // Thanh toán thất bại
            return $this->processFailedPayment($order, 'vnpay', $vnp_TransactionNo, $request->all());
        }
    }

    /**
     * Xử lý thanh toán thành công
     */
    private function processSuccessfulPayment($order, $provider, $transactionId, $meta = [])
    {
        // Kiểm tra order đã được thanh toán chưa
        if ($order->status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Đơn hàng này đã được thanh toán.');
        }

        DB::beginTransaction();
        try {
            $this->completePayment($order, $provider, $transactionId, $meta);
            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Thanh toán thành công! Bạn đã được đăng ký vào các khóa học.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error', [
                'order_id' => $order->id,
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('orders.show', $order)
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý thanh toán thất bại
     */
    private function processFailedPayment($order, $provider, $transactionId, $meta = [])
    {
        // Create payment record with failed status
        Payment::create([
            'order_id' => $order->id,
            'provider' => $provider,
            'transaction_id' => $transactionId ?: 'FAILED_' . time(),
            'amount' => $order->total,
            'status' => 'failed',
            'meta' => $meta,
        ]);

        // Update order status
        if ($order->status === 'pending') {
            $order->update(['status' => 'failed']);
        }

        return redirect()->route('orders.show', $order)
            ->with('error', 'Thanh toán thất bại. Vui lòng thử lại.');
    }

    /**
     * Hoàn tất thanh toán và đăng ký khóa học
     */
    private function completePayment($order, $provider, $transactionId, $meta = [])
    {
        // Update order status
        $order->update(['status' => 'paid']);

        // Create payment record
        Payment::create([
            'order_id' => $order->id,
            'provider' => $provider,
            'transaction_id' => $transactionId ?: 'TXN' . time() . rand(1000, 9999),
            'amount' => $order->total,
            'status' => 'succeeded',
            'meta' => $meta,
        ]);

        // Create enrollments
        foreach ($order->items as $item) {
            // Check if already enrolled
            $existingEnrollment = Enrollment::where('user_id', $order->user_id)
                ->where('course_id', $item->course_id)
                ->where('status', 'active')
                ->first();

            if (!$existingEnrollment) {
                Enrollment::create([
                    'user_id' => $order->user_id,
                    'course_id' => $item->course_id,
                    'status' => 'active',
                    'enrolled_at' => now(),
                ]);

                // Update course enrolled count
                $item->course->increment('enrolled_students');
            }
        }

        // Clear cart and coupon
        session()->forget('cart');
        session()->forget('coupon_code');
    }
}


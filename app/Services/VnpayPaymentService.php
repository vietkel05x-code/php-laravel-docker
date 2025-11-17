<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VnpayPaymentService
{
    private $tmnCode;
    private $hashSecret;
    private $url;
    private $returnUrl;
    private $isSandbox;

    public function __construct()
    {
        $this->tmnCode = config('services.vnpay.tmn_code', '');
        $this->hashSecret = config('services.vnpay.hash_secret', '');
        $this->isSandbox = config('services.vnpay.sandbox', true);
        
        // VNPay endpoints
        if ($this->isSandbox) {
            $this->url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        } else {
            $this->url = 'https://vnpayment.vn/paymentv2/vpcpay.html';
        }
        
        $this->returnUrl = config('services.vnpay.return_url', route('payment.vnpay.return'));
    }

    /**
     * Tạo payment URL từ VNPay
     */
    public function createPayment($orderId, $amount, $orderInfo, $orderType = 'other')
    {
        // Nếu không có API keys, sử dụng giả lập
        if (empty($this->tmnCode) || empty($this->hashSecret)) {
            return null; // Sẽ redirect đến trang giả lập
        }

        $vnp_TxnRef = $orderId . '_' . time();
        $vnp_Amount = $amount * 100; // VNPay yêu cầu số tiền nhân 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        $vnp_CreateDate = date('YmdHis');

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_Amount' => $vnp_Amount,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => $vnp_CreateDate,
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $vnp_IpAddr,
            'vnp_Locale' => $vnp_Locale,
            'vnp_OrderInfo' => $orderInfo,
            'vnp_OrderType' => $orderType,
            'vnp_ReturnUrl' => $this->returnUrl . '?order=' . $orderId,
            'vnp_TxnRef' => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $vnp_SecureHash = hash_hmac('sha512', $query, $this->hashSecret);
        $inputData['vnp_SecureHash'] = $vnp_SecureHash;
        $query .= '&vnp_SecureHash=' . $vnp_SecureHash;

        return $this->url . '?' . $query;
    }

    /**
     * Xác thực callback từ VNPay
     */
    public function verifyCallback($data)
    {
        if (empty($this->hashSecret)) {
            return false;
        }

        $vnp_SecureHash = $data['vnp_SecureHash'] ?? '';
        unset($data['vnp_SecureHash']);
        
        ksort($data);
        $query = http_build_query($data);
        $expectedHash = hash_hmac('sha512', $query, $this->hashSecret);
        
        return hash_equals($expectedHash, $vnp_SecureHash);
    }
}


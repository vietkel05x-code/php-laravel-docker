<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MomoPaymentService
{
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $endpoint;
    private $returnUrl;
    private $notifyUrl;
    private $isSandbox;

    public function __construct()
    {
        $this->partnerCode = config('services.momo.partner_code', 'MOMOBKUN20180529');
        $this->accessKey = config('services.momo.access_key', '');
        $this->secretKey = config('services.momo.secret_key', '');
        $this->isSandbox = config('services.momo.sandbox', true);
        
        // MoMo endpoints
        if ($this->isSandbox) {
            $this->endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';
        } else {
            $this->endpoint = 'https://payment.momo.vn/v2/gateway/api/create';
        }
        
        $returnUrl = config('services.momo.return_url');
        $notifyUrl = config('services.momo.notify_url');
        
        // Đảm bảo URL không có trailing slash và đúng format
        $baseReturnUrl = $returnUrl ?: url('/payment/momo/return');
        $baseNotifyUrl = $notifyUrl ?: url('/payment/momo/notify');
        
        $this->returnUrl = rtrim($baseReturnUrl, '/');
        $this->notifyUrl = rtrim($baseNotifyUrl, '/');
    }

    /**
     * Tạo payment URL từ MoMo
     */
    public function createPayment($orderId, $amount, $orderInfo, $extraData = '')
    {
        // Nếu không có API keys, sử dụng giả lập
        if (empty($this->accessKey) || empty($this->secretKey)) {
            return null; // Sẽ redirect đến trang giả lập
        }

        $requestId = time() . '';
        $momoOrderId = $orderId . '_' . time();
        
        // Tạo redirectUrl với query string
        $redirectUrlWithQuery = $this->returnUrl . '?order=' . urlencode($momoOrderId);
        
        // Chuẩn bị data trước
        $data = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => 'E-Learning',
            'storeId' => 'E-Learning Store',
            'requestId' => $requestId,
            'amount' => (int)$amount,
            'orderId' => $momoOrderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrlWithQuery,
            'ipnUrl' => $this->notifyUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => 'captureWallet',
        ];
        
        // Tạo raw signature - thứ tự phải đúng theo alphabet
        // Theo tài liệu MoMo, orderInfo trong rawHash KHÔNG được encode, nhưng trong data gửi đi có thể encode
        // Thử không encode orderInfo trong rawHash trước
        $rawHash = "accessKey={$this->accessKey}&amount={$data['amount']}&extraData={$extraData}&ipnUrl={$this->notifyUrl}&orderId={$momoOrderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}&redirectUrl={$redirectUrlWithQuery}&requestId={$requestId}&requestType=captureWallet";
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);
        
        // Log rawHash để debug (ẩn accessKey và secretKey)
        Log::debug('MoMo Signature RawHash', [
            'rawHash' => str_replace([$this->accessKey, $this->secretKey], ['***ACCESS_KEY***', '***SECRET_KEY***'], $rawHash),
        ]);
        
        // Thêm signature vào data
        $data['signature'] = $signature;

        try {
            Log::info('MoMo Payment Request', [
                'endpoint' => $this->endpoint,
                'data' => array_merge($data, ['signature' => '***']), // Ẩn signature trong log
            ]);

            $response = Http::timeout(30)->post($this->endpoint, $data);
            
            Log::info('MoMo Payment Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['payUrl'])) {
                    return $result['payUrl'];
                }
                
                // Log lỗi từ MoMo
                if (isset($result['message'])) {
                    Log::error('MoMo Payment Error', [
                        'message' => $result['message'],
                        'resultCode' => $result['resultCode'] ?? 'N/A',
                        'response' => $result,
                    ]);
                }
            } else {
                Log::error('MoMo Payment HTTP Error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('MoMo Payment Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return null;
        }
    }

    /**
     * Xác thực callback từ MoMo
     */
    public function verifyCallback($data)
    {
        if (empty($this->secretKey)) {
            return false;
        }

        $signature = $data['signature'] ?? '';
        unset($data['signature']);
        
        // Sắp xếp và tạo raw hash theo format MoMo
        ksort($data);
        $rawHash = http_build_query($data);
        $expectedSignature = hash_hmac('sha256', $rawHash, $this->secretKey);
        
        return hash_equals($expectedSignature, $signature);
    }
}


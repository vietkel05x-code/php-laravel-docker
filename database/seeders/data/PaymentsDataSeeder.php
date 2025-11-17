<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('payments')->truncate();
        
        $data = [
  0 => [
    'id' => 1,
    'order_id' => 6,
    'provider' => 'momo',
    'transaction_id' => 'TXN17629424511711',
    'amount' => '699000.00',
    'status' => 'failed',
    'meta' => '{"payment_time":"2025-11-12 10:14:11","method":"momo","reason":"User cancelled"}',
    'created_at' => '2025-11-12 10:14:11',
    'updated_at' => '2025-11-12 10:14:11',
  ],
  1 => [
    'id' => 2,
    'order_id' => 7,
    'provider' => 'momo',
    'transaction_id' => 'TXN17629424823552',
    'amount' => '699000.00',
    'status' => 'succeeded',
    'meta' => '{"payment_time":"2025-11-12 10:14:42","method":"momo"}',
    'created_at' => '2025-11-12 10:14:42',
    'updated_at' => '2025-11-12 10:14:42',
  ],
  2 => [
    'id' => 3,
    'order_id' => 9,
    'provider' => 'momo',
    'transaction_id' => 'TXN17629432377204',
    'amount' => '379000.00',
    'status' => 'failed',
    'meta' => '{"payment_time":"2025-11-12 10:27:17","method":"momo","reason":"User cancelled"}',
    'created_at' => '2025-11-12 10:27:17',
    'updated_at' => '2025-11-12 10:27:17',
  ],
  3 => [
    'id' => 4,
    'order_id' => 10,
    'provider' => 'momo',
    'transaction_id' => '1762943975608',
    'amount' => '379000.00',
    'status' => 'failed',
    'meta' => '{"order":"10_1762943377","partnerCode":"MOMOFOEL20251112_TEST","orderId":"10_1762943377","requestId":"1762943377","amount":"379000","orderInfo":"Thanh toan don hang #ORD-YCENTADD","orderType":"momo_wallet","transId":"1762943975608","resultCode":"1005","message":"Giao d\\u1ecbch \\u0111\\u00e3 h\\u1ebft h\\u1ea1n ho\\u1eb7c kh\\u00f4ng t\\u1ed3n t\\u1ea1i.","payType":null,"responseTime":"1762943975634","extraData":null,"signature":"110c5db86ea4553c9fc9f625375246b6a92800329ca0d0e097e905bbbca2f825"}',
    'created_at' => '2025-11-12 10:39:44',
    'updated_at' => '2025-11-12 10:39:44',
  ],
  4 => [
    'id' => 5,
    'order_id' => 11,
    'provider' => 'momo',
    'transaction_id' => '1762944771481',
    'amount' => '379000.00',
    'status' => 'failed',
    'meta' => '{"order":"11_1762944161","partnerCode":"MOMOFOEL20251112_TEST","orderId":"11_1762944161","requestId":"1762944161","amount":"379000","orderInfo":"Thanh toan don hang #ORD-QAPDIF2D","orderType":"momo_wallet","transId":"1762944771481","resultCode":"1005","message":"Giao d\\u1ecbch \\u0111\\u00e3 h\\u1ebft h\\u1ea1n ho\\u1eb7c kh\\u00f4ng t\\u1ed3n t\\u1ea1i.","payType":null,"responseTime":"1762944771483","extraData":null,"signature":"15a7b3ca23b0ba5a99791dde7d10102a12314754de281d7d9b4434e05a66c42a"}',
    'created_at' => '2025-11-12 10:52:57',
    'updated_at' => '2025-11-12 10:52:57',
  ],
  5 => [
    'id' => 6,
    'order_id' => 12,
    'provider' => 'momo',
    'transaction_id' => '4611181379',
    'amount' => '379000.00',
    'status' => 'succeeded',
    'meta' => '{"order":"12_1762944784","partnerCode":"MOMOFOEL20251112_TEST","orderId":"12_1762944784","requestId":"1762944784","amount":"379000","orderInfo":"Thanh toan don hang #ORD-G4WO92WG","orderType":"momo_wallet","transId":"4611181379","resultCode":"0","message":"Th\\u00e0nh c\\u00f4ng.","payType":"qr","responseTime":"1762944799107","extraData":null,"signature":"feb6bd439eeb28beee56ef3ec54330599046534e2a4c037557a9c574e3c75d19"}',
    'created_at' => '2025-11-12 10:53:25',
    'updated_at' => '2025-11-12 10:53:25',
  ],
  6 => [
    'id' => 7,
    'order_id' => 13,
    'provider' => 'momo',
    'transaction_id' => '1762948204780',
    'amount' => '579000.00',
    'status' => 'failed',
    'meta' => '{"order":"13_1762948202","partnerCode":"MOMOFOEL20251112_TEST","orderId":"13_1762948202","requestId":"1762948202","amount":"579000","orderInfo":"Thanh toan don hang #ORD-RWIW2UDC","orderType":"momo_wallet","transId":"1762948204780","resultCode":"1006","message":"Giao d\\u1ecbch b\\u1ecb t\\u1eeb ch\\u1ed1i b\\u1edfi ng\\u01b0\\u1eddi d\\u00f9ng.","payType":null,"responseTime":"1762948204783","extraData":null,"signature":"f0f1af40f9217e57324fa2f05ddad27c6521793b7ce6fec945f15510f64febb8"}',
    'created_at' => '2025-11-12 11:50:11',
    'updated_at' => '2025-11-12 11:50:11',
  ],
  7 => [
    'id' => 8,
    'order_id' => 14,
    'provider' => 'vnpay',
    'transaction_id' => 'TXN17629482561694',
    'amount' => '579000.00',
    'status' => 'failed',
    'meta' => '{"payment_time":"2025-11-12 11:50:56","method":"vnpay","reason":"User cancelled"}',
    'created_at' => '2025-11-12 11:50:56',
    'updated_at' => '2025-11-12 11:50:56',
  ],
  8 => [
    'id' => 9,
    'order_id' => 15,
    'provider' => 'momo',
    'transaction_id' => '1762950051181',
    'amount' => '978000.00',
    'status' => 'failed',
    'meta' => '{"order":"15_1762950034","partnerCode":"MOMOFOEL20251112_TEST","orderId":"15_1762950034","requestId":"1762950034","amount":"978000","orderInfo":"Thanh toan don hang #ORD-JG9K7ORS","orderType":"momo_wallet","transId":"1762950051181","resultCode":"1006","message":"Giao d\\u1ecbch b\\u1ecb t\\u1eeb ch\\u1ed1i b\\u1edfi ng\\u01b0\\u1eddi d\\u00f9ng.","payType":null,"responseTime":"1762950051183","extraData":null,"signature":"54e938a76c0f8d31136a27e4fc31f54166ae7d6713da4b25cd53d25308ea8550"}',
    'created_at' => '2025-11-12 12:20:57',
    'updated_at' => '2025-11-12 12:20:57',
  ],
  9 => [
    'id' => 10,
    'order_id' => 15,
    'provider' => 'momo',
    'transaction_id' => '1762950051181',
    'amount' => '978000.00',
    'status' => 'failed',
    'meta' => '{"order":"15_1762950034","partnerCode":"MOMOFOEL20251112_TEST","orderId":"15_1762950034","requestId":"1762950034","amount":"978000","orderInfo":"Thanh toan don hang #ORD-JG9K7ORS","orderType":"momo_wallet","transId":"1762950051181","resultCode":"1006","message":"Giao d\\u1ecbch b\\u1ecb t\\u1eeb ch\\u1ed1i b\\u1edfi ng\\u01b0\\u1eddi d\\u00f9ng.","payType":null,"responseTime":"1762950051183","extraData":null,"signature":"54e938a76c0f8d31136a27e4fc31f54166ae7d6713da4b25cd53d25308ea8550"}',
    'created_at' => '2025-11-12 12:20:57',
    'updated_at' => '2025-11-12 12:20:57',
  ],
  10 => [
    'id' => 11,
    'order_id' => 16,
    'provider' => 'momo',
    'transaction_id' => '4611400441',
    'amount' => '379000.00',
    'status' => 'succeeded',
    'meta' => '{"order":"16_1762970271","partnerCode":"MOMOFOEL20251112_TEST","orderId":"16_1762970271","requestId":"1762970271","amount":"379000","orderInfo":"Thanh toan don hang #ORD-TV8QEDFZ","orderType":"momo_wallet","transId":"4611400441","resultCode":"0","message":"Th\\u00e0nh c\\u00f4ng.","payType":"qr","responseTime":"1762970297812","extraData":null,"signature":"91ee16f232320173ac391f2ce2ef8f70829c51aad99473de593dac530686a3bf"}',
    'created_at' => '2025-11-12 17:58:24',
    'updated_at' => '2025-11-12 17:58:24',
  ],
  11 => [
    'id' => 12,
    'order_id' => 17,
    'provider' => 'momo',
    'transaction_id' => '1763140880341',
    'amount' => '389400.00',
    'status' => 'failed',
    'meta' => '{"order":"17_1763140876","partnerCode":"MOMOFOEL20251112_TEST","orderId":"17_1763140876","requestId":"1763140876","amount":"389400","orderInfo":"Thanh toan don hang #ORD-LHJAPSA0","orderType":"momo_wallet","transId":"1763140880341","resultCode":"1006","message":"Giao d\\u1ecbch b\\u1ecb t\\u1eeb ch\\u1ed1i b\\u1edfi ng\\u01b0\\u1eddi d\\u00f9ng.","payType":null,"responseTime":"1763140880343","extraData":null,"signature":"da54fc237e858587070c471bb62c13b80e81b3a3db6b23dd4323c143146a67fd"}',
    'created_at' => '2025-11-14 17:21:27',
    'updated_at' => '2025-11-14 17:21:27',
  ],
  12 => [
    'id' => 13,
    'order_id' => 18,
    'provider' => 'bank_transfer',
    'transaction_id' => 'BANK17631794884040',
    'amount' => '649000.00',
    'status' => 'initiated',
    'meta' => '{"payment_time":"2025-11-15 04:04:48","method":"bank_transfer","bank_name":"tju","transaction_code":"try","note":null}',
    'created_at' => '2025-11-15 04:04:48',
    'updated_at' => '2025-11-15 04:04:48',
  ],
  13 => [
    'id' => 14,
    'order_id' => 18,
    'provider' => 'vnpay',
    'transaction_id' => 'TXN17631795186177',
    'amount' => '649000.00',
    'status' => 'succeeded',
    'meta' => '{"payment_time":"2025-11-15 04:05:18","method":"vnpay"}',
    'created_at' => '2025-11-15 04:05:18',
    'updated_at' => '2025-11-15 04:05:18',
  ],
  14 => [
    'id' => 15,
    'order_id' => 19,
    'provider' => 'vnpay',
    'transaction_id' => 'TXN17632307806415',
    'amount' => '379000.00',
    'status' => 'succeeded',
    'meta' => '{"payment_time":"2025-11-15 18:19:40","method":"vnpay"}',
    'created_at' => '2025-11-15 18:19:40',
    'updated_at' => '2025-11-15 18:19:40',
  ],
];
        
        // Insert data
        DB::table('payments')->insert($data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('payments', 'id'),
                    (SELECT MAX(id) FROM payments)
                )
            ");
        } catch (\Exception $e) {
            // Ignore if table doesn't have id column
        }
    }
}

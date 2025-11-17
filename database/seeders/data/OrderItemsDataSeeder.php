<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('order_items')->truncate();
        
        $data = [
  0 => [
    'id' => 1,
    'order_id' => 1,
    'course_id' => 13,
    'price' => '550000.00',
    'created_at' => '2025-11-11 19:13:45',
    'updated_at' => '2025-11-11 19:13:45',
  ],
  1 => [
    'id' => 2,
    'order_id' => 2,
    'course_id' => 31,
    'price' => '379000.00',
    'created_at' => '2025-11-11 19:30:40',
    'updated_at' => '2025-11-11 19:30:40',
  ],
  2 => [
    'id' => 3,
    'order_id' => 3,
    'course_id' => 24,
    'price' => '449000.00',
    'created_at' => '2025-11-11 19:43:22',
    'updated_at' => '2025-11-11 19:43:22',
  ],
  3 => [
    'id' => 4,
    'order_id' => 4,
    'course_id' => 30,
    'price' => '699000.00',
    'created_at' => '2025-11-12 09:46:10',
    'updated_at' => '2025-11-12 09:46:10',
  ],
  4 => [
    'id' => 5,
    'order_id' => 5,
    'course_id' => 30,
    'price' => '699000.00',
    'created_at' => '2025-11-12 10:13:53',
    'updated_at' => '2025-11-12 10:13:53',
  ],
  5 => [
    'id' => 6,
    'order_id' => 6,
    'course_id' => 30,
    'price' => '699000.00',
    'created_at' => '2025-11-12 10:14:06',
    'updated_at' => '2025-11-12 10:14:06',
  ],
  6 => [
    'id' => 7,
    'order_id' => 7,
    'course_id' => 30,
    'price' => '699000.00',
    'created_at' => '2025-11-12 10:14:36',
    'updated_at' => '2025-11-12 10:14:36',
  ],
  7 => [
    'id' => 8,
    'order_id' => 8,
    'course_id' => 31,
    'price' => '379000.00',
    'created_at' => '2025-11-12 10:19:51',
    'updated_at' => '2025-11-12 10:19:51',
  ],
  8 => [
    'id' => 9,
    'order_id' => 9,
    'course_id' => 31,
    'price' => '379000.00',
    'created_at' => '2025-11-12 10:24:02',
    'updated_at' => '2025-11-12 10:24:02',
  ],
  9 => [
    'id' => 10,
    'order_id' => 10,
    'course_id' => 31,
    'price' => '379000.00',
    'created_at' => '2025-11-12 10:29:37',
    'updated_at' => '2025-11-12 10:29:37',
  ],
  10 => [
    'id' => 11,
    'order_id' => 11,
    'course_id' => 31,
    'price' => '379000.00',
    'created_at' => '2025-11-12 10:42:41',
    'updated_at' => '2025-11-12 10:42:41',
  ],
  11 => [
    'id' => 12,
    'order_id' => 12,
    'course_id' => 31,
    'price' => '379000.00',
    'created_at' => '2025-11-12 10:53:04',
    'updated_at' => '2025-11-12 10:53:04',
  ],
  12 => [
    'id' => 13,
    'order_id' => 13,
    'course_id' => 26,
    'price' => '579000.00',
    'created_at' => '2025-11-12 11:50:02',
    'updated_at' => '2025-11-12 11:50:02',
  ],
  13 => [
    'id' => 14,
    'order_id' => 14,
    'course_id' => 26,
    'price' => '579000.00',
    'created_at' => '2025-11-12 11:50:28',
    'updated_at' => '2025-11-12 11:50:28',
  ],
  14 => [
    'id' => 15,
    'order_id' => 15,
    'course_id' => 26,
    'price' => '579000.00',
    'created_at' => '2025-11-12 12:20:34',
    'updated_at' => '2025-11-12 12:20:34',
  ],
  15 => [
    'id' => 16,
    'order_id' => 15,
    'course_id' => 19,
    'price' => '399000.00',
    'created_at' => '2025-11-12 12:20:34',
    'updated_at' => '2025-11-12 12:20:34',
  ],
  16 => [
    'id' => 17,
    'order_id' => 16,
    'course_id' => 31,
    'price' => '379000.00',
    'created_at' => '2025-11-12 17:57:50',
    'updated_at' => '2025-11-12 17:57:50',
  ],
  17 => [
    'id' => 18,
    'order_id' => 17,
    'course_id' => 32,
    'price' => '649000.00',
    'created_at' => '2025-11-14 17:21:15',
    'updated_at' => '2025-11-14 17:21:15',
  ],
  18 => [
    'id' => 19,
    'order_id' => 18,
    'course_id' => 32,
    'price' => '649000.00',
    'created_at' => '2025-11-15 04:04:42',
    'updated_at' => '2025-11-15 04:04:42',
  ],
  19 => [
    'id' => 20,
    'order_id' => 19,
    'course_id' => 31,
    'price' => '379000.00',
    'created_at' => '2025-11-15 18:19:38',
    'updated_at' => '2025-11-15 18:19:38',
  ],
  20 => [
    'id' => 21,
    'order_id' => 20,
    'course_id' => 35,
    'price' => '0.00',
    'created_at' => '2025-11-16 07:40:16',
    'updated_at' => '2025-11-16 07:40:16',
  ],
  21 => [
    'id' => 22,
    'order_id' => 21,
    'course_id' => 35,
    'price' => '0.00',
    'created_at' => '2025-11-16 15:40:35',
    'updated_at' => '2025-11-16 15:40:35',
  ],
  22 => [
    'id' => 23,
    'order_id' => 22,
    'course_id' => 35,
    'price' => '0.00',
    'created_at' => '2025-11-16 15:41:34',
    'updated_at' => '2025-11-16 15:41:34',
  ],
];
        
        // Insert data
        DB::table('order_items')->insert($data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('order_items', 'id'),
                    (SELECT MAX(id) FROM order_items)
                )
            ");
        } catch (\Exception $e) {
            // Ignore if table doesn't have id column
        }
    }
}

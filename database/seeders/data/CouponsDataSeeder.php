<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponsDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('coupons')->truncate();
        
        $data = [
  0 => [
    'id' => 1,
    'code' => 'BLACKFRIDAY',
    'type' => 'percent',
    'value' => '40.00',
    'starts_at' => '2025-11-12 02:42:00',
    'ends_at' => '2025-11-28 02:42:00',
    'max_uses' => 1,
    'uses' => 0,
    'created_at' => '2025-11-11 19:42:25',
    'updated_at' => '2025-11-11 19:42:25',
  ],
];
        
        // Insert data
        DB::table('coupons')->insert($data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('coupons', 'id'),
                    (SELECT MAX(id) FROM coupons)
                )
            ");
        } catch (\Exception $e) {
            // Ignore if table doesn't have id column
        }
    }
}

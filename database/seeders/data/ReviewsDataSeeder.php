<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('reviews')->truncate();
        
        $data = [
  0 => [
    'id' => 2,
    'user_id' => 2,
    'course_id' => 13,
    'rating' => 5,
    'content' => 'ád',
    'created_at' => '2025-11-11 19:20:17',
    'updated_at' => '2025-11-11 19:20:17',
  ],
  1 => [
    'id' => 3,
    'user_id' => 1,
    'course_id' => 35,
    'rating' => 5,
    'content' => 'Quá tốt rồi',
    'created_at' => '2025-11-16 16:00:36',
    'updated_at' => '2025-11-16 16:00:36',
  ],
];
        
        // Insert data
        DB::table('reviews')->insert($data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('reviews', 'id'),
                    (SELECT MAX(id) FROM reviews)
                )
            ");
        } catch (\Exception $e) {
            // Ignore if table doesn't have id column
        }
    }
}

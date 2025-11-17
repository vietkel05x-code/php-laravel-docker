<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnrollmentsDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('enrollments')->truncate();
        
        $data = [
  0 => [
    'id' => 1,
    'user_id' => 2,
    'course_id' => 13,
    'status' => 'active',
    'enrolled_at' => '2025-11-11 19:13:45',
    'created_at' => '2025-11-11 19:13:45',
    'updated_at' => '2025-11-11 19:13:45',
  ],
  1 => [
    'id' => 2,
    'user_id' => 1,
    'course_id' => 31,
    'status' => 'active',
    'enrolled_at' => '2025-11-11 19:30:40',
    'created_at' => '2025-11-11 19:30:40',
    'updated_at' => '2025-11-11 19:30:40',
  ],
  2 => [
    'id' => 3,
    'user_id' => 1,
    'course_id' => 24,
    'status' => 'active',
    'enrolled_at' => '2025-11-11 19:43:22',
    'created_at' => '2025-11-11 19:43:22',
    'updated_at' => '2025-11-11 19:43:22',
  ],
  3 => [
    'id' => 4,
    'user_id' => 2,
    'course_id' => 30,
    'status' => 'active',
    'enrolled_at' => '2025-11-12 10:14:42',
    'created_at' => '2025-11-12 10:14:42',
    'updated_at' => '2025-11-12 10:14:42',
  ],
  4 => [
    'id' => 5,
    'user_id' => 2,
    'course_id' => 31,
    'status' => 'active',
    'enrolled_at' => '2025-11-12 10:53:25',
    'created_at' => '2025-11-12 10:53:25',
    'updated_at' => '2025-11-12 10:53:25',
  ],
  5 => [
    'id' => 6,
    'user_id' => 5,
    'course_id' => 31,
    'status' => 'active',
    'enrolled_at' => '2025-11-12 17:58:24',
    'created_at' => '2025-11-12 17:58:24',
    'updated_at' => '2025-11-12 17:58:24',
  ],
  6 => [
    'id' => 7,
    'user_id' => 2,
    'course_id' => 32,
    'status' => 'active',
    'enrolled_at' => '2025-11-15 04:05:18',
    'created_at' => '2025-11-15 04:05:18',
    'updated_at' => '2025-11-15 04:05:18',
  ],
  7 => [
    'id' => 8,
    'user_id' => 3,
    'course_id' => 31,
    'status' => 'active',
    'enrolled_at' => '2025-11-15 18:19:40',
    'created_at' => '2025-11-15 18:19:40',
    'updated_at' => '2025-11-15 18:19:40',
  ],
  8 => [
    'id' => 9,
    'user_id' => 1,
    'course_id' => 35,
    'status' => 'active',
    'enrolled_at' => '2025-11-16 07:40:16',
    'created_at' => '2025-11-16 07:40:16',
    'updated_at' => '2025-11-16 07:40:16',
  ],
  9 => [
    'id' => 10,
    'user_id' => 2,
    'course_id' => 35,
    'status' => 'active',
    'enrolled_at' => '2025-11-16 15:40:35',
    'created_at' => '2025-11-16 15:40:35',
    'updated_at' => '2025-11-16 15:40:35',
  ],
  10 => [
    'id' => 11,
    'user_id' => 5,
    'course_id' => 35,
    'status' => 'active',
    'enrolled_at' => '2025-11-16 15:41:34',
    'created_at' => '2025-11-16 15:41:34',
    'updated_at' => '2025-11-16 15:41:34',
  ],
];
        
        // Insert data
        DB::table('enrollments')->insert($data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('enrollments', 'id'),
                    (SELECT MAX(id) FROM enrollments)
                )
            ");
        } catch (\Exception $e) {
            // Ignore if table doesn't have id column
        }
    }
}

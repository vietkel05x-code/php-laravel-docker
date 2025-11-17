<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonProgressDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('lesson_progress')->truncate();
        
        $data = [
  0 => [
    'id' => 1,
    'user_id' => 1,
    'lesson_id' => 2,
    'is_completed' => 1,
    'completed_at' => '2025-11-11 19:38:48',
    'created_at' => '2025-11-11 19:38:48',
    'updated_at' => '2025-11-11 19:38:48',
  ],
  1 => [
    'id' => 2,
    'user_id' => 5,
    'lesson_id' => 4,
    'is_completed' => 1,
    'completed_at' => '2025-11-12 17:59:16',
    'created_at' => '2025-11-12 17:59:16',
    'updated_at' => '2025-11-12 17:59:16',
  ],
  2 => [
    'id' => 3,
    'user_id' => 5,
    'lesson_id' => 2,
    'is_completed' => 1,
    'completed_at' => '2025-11-12 17:59:59',
    'created_at' => '2025-11-12 17:59:59',
    'updated_at' => '2025-11-12 17:59:59',
  ],
  3 => [
    'id' => 4,
    'user_id' => 5,
    'lesson_id' => 5,
    'is_completed' => 1,
    'completed_at' => '2025-11-12 18:17:32',
    'created_at' => '2025-11-12 18:17:32',
    'updated_at' => '2025-11-12 18:17:32',
  ],
  4 => [
    'id' => 5,
    'user_id' => 5,
    'lesson_id' => 7,
    'is_completed' => 1,
    'completed_at' => '2025-11-12 18:18:43',
    'created_at' => '2025-11-12 18:18:43',
    'updated_at' => '2025-11-12 18:18:43',
  ],
  5 => [
    'id' => 6,
    'user_id' => 2,
    'lesson_id' => 8,
    'is_completed' => 1,
    'completed_at' => '2025-11-12 18:35:32',
    'created_at' => '2025-11-12 18:35:32',
    'updated_at' => '2025-11-12 18:35:32',
  ],
  6 => [
    'id' => 7,
    'user_id' => 2,
    'lesson_id' => 9,
    'is_completed' => 1,
    'completed_at' => '2025-11-12 18:35:58',
    'created_at' => '2025-11-12 18:35:58',
    'updated_at' => '2025-11-12 18:35:58',
  ],
  7 => [
    'id' => 8,
    'user_id' => 1,
    'lesson_id' => 4,
    'is_completed' => 1,
    'completed_at' => '2025-11-14 05:57:17',
    'created_at' => '2025-11-14 05:57:17',
    'updated_at' => '2025-11-14 05:57:17',
  ],
  8 => [
    'id' => 9,
    'user_id' => 1,
    'lesson_id' => 5,
    'is_completed' => 1,
    'completed_at' => '2025-11-14 05:57:26',
    'created_at' => '2025-11-14 05:57:26',
    'updated_at' => '2025-11-14 05:57:26',
  ],
  9 => [
    'id' => 10,
    'user_id' => 1,
    'lesson_id' => 7,
    'is_completed' => 1,
    'completed_at' => '2025-11-14 05:58:21',
    'created_at' => '2025-11-14 05:58:21',
    'updated_at' => '2025-11-14 05:58:21',
  ],
  10 => [
    'id' => 11,
    'user_id' => 2,
    'lesson_id' => 10,
    'is_completed' => 1,
    'completed_at' => '2025-11-14 17:22:25',
    'created_at' => '2025-11-14 17:22:25',
    'updated_at' => '2025-11-14 17:22:25',
  ],
  11 => [
    'id' => 12,
    'user_id' => 2,
    'lesson_id' => 4,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 03:14:32',
    'created_at' => '2025-11-15 03:14:32',
    'updated_at' => '2025-11-15 03:14:32',
  ],
  12 => [
    'id' => 13,
    'user_id' => 2,
    'lesson_id' => 2,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 03:52:19',
    'created_at' => '2025-11-15 03:52:19',
    'updated_at' => '2025-11-15 03:52:19',
  ],
  13 => [
    'id' => 14,
    'user_id' => 2,
    'lesson_id' => 11,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 03:56:31',
    'created_at' => '2025-11-15 03:56:31',
    'updated_at' => '2025-11-15 03:56:31',
  ],
  14 => [
    'id' => 15,
    'user_id' => 2,
    'lesson_id' => 12,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 03:56:50',
    'created_at' => '2025-11-15 03:56:50',
    'updated_at' => '2025-11-15 03:56:50',
  ],
  15 => [
    'id' => 16,
    'user_id' => 2,
    'lesson_id' => 13,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 04:05:32',
    'created_at' => '2025-11-15 04:05:32',
    'updated_at' => '2025-11-15 04:05:32',
  ],
  16 => [
    'id' => 17,
    'user_id' => 2,
    'lesson_id' => 14,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 04:06:36',
    'created_at' => '2025-11-15 04:06:36',
    'updated_at' => '2025-11-15 04:06:36',
  ],
  17 => [
    'id' => 18,
    'user_id' => 2,
    'lesson_id' => 15,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 04:18:01',
    'created_at' => '2025-11-15 04:18:01',
    'updated_at' => '2025-11-15 04:18:01',
  ],
  18 => [
    'id' => 19,
    'user_id' => 2,
    'lesson_id' => 16,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 14:36:29',
    'created_at' => '2025-11-15 14:36:29',
    'updated_at' => '2025-11-15 14:36:29',
  ],
  19 => [
    'id' => 20,
    'user_id' => 2,
    'lesson_id' => 5,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 15:24:01',
    'created_at' => '2025-11-15 15:24:01',
    'updated_at' => '2025-11-15 15:24:01',
  ],
  20 => [
    'id' => 21,
    'user_id' => 3,
    'lesson_id' => 2,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 18:20:12',
    'created_at' => '2025-11-15 18:20:12',
    'updated_at' => '2025-11-15 18:20:12',
  ],
  21 => [
    'id' => 22,
    'user_id' => 3,
    'lesson_id' => 5,
    'is_completed' => 1,
    'completed_at' => '2025-11-15 18:35:39',
    'created_at' => '2025-11-15 18:35:39',
    'updated_at' => '2025-11-15 18:35:39',
  ],
  22 => [
    'id' => 23,
    'user_id' => 2,
    'lesson_id' => 7,
    'is_completed' => 1,
    'completed_at' => '2025-11-16 05:43:25',
    'created_at' => '2025-11-16 05:43:25',
    'updated_at' => '2025-11-16 05:43:25',
  ],
  23 => [
    'id' => 24,
    'user_id' => 1,
    'lesson_id' => 17,
    'is_completed' => 1,
    'completed_at' => '2025-11-16 07:42:54',
    'created_at' => '2025-11-16 07:42:54',
    'updated_at' => '2025-11-16 07:42:54',
  ],
  24 => [
    'id' => 25,
    'user_id' => 1,
    'lesson_id' => 18,
    'is_completed' => 1,
    'completed_at' => '2025-11-16 07:43:39',
    'created_at' => '2025-11-16 07:43:39',
    'updated_at' => '2025-11-16 07:43:39',
  ],
  25 => [
    'id' => 26,
    'user_id' => 1,
    'lesson_id' => 19,
    'is_completed' => 1,
    'completed_at' => '2025-11-16 07:52:42',
    'created_at' => '2025-11-16 07:52:42',
    'updated_at' => '2025-11-16 07:52:42',
  ],
];
        
        // Insert data
        DB::table('lesson_progress')->insert($data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('lesson_progress', 'id'),
                    (SELECT MAX(id) FROM lesson_progress)
                )
            ");
        } catch (\Exception $e) {
            // Ignore if table doesn't have id column
        }
    }
}

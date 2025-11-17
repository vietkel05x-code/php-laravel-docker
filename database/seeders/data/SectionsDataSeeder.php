<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('sections')->truncate();
        
        $data = [
  0 => [
    'id' => 2,
    'course_id' => 31,
    'title' => 'Phần 1',
    'position' => 1,
    'created_at' => '2025-11-11 19:26:53',
    'updated_at' => '2025-11-11 19:26:53',
  ],
  1 => [
    'id' => 3,
    'course_id' => 32,
    'title' => 'gf',
    'position' => 1,
    'created_at' => '2025-11-12 05:35:46',
    'updated_at' => '2025-11-12 05:35:46',
  ],
  2 => [
    'id' => 5,
    'course_id' => 31,
    'title' => 'phần 2',
    'position' => 2,
    'created_at' => '2025-11-12 18:11:22',
    'updated_at' => '2025-11-12 18:11:22',
  ],
  3 => [
    'id' => 6,
    'course_id' => 30,
    'title' => 'Phần 1: Hướng Dẫn Viết Prompt Hiệu Quả',
    'position' => 1,
    'created_at' => '2025-11-12 18:24:52',
    'updated_at' => '2025-11-12 18:24:52',
  ],
  4 => [
    'id' => 7,
    'course_id' => 30,
    'title' => 'Phần 2: Thực Hành Với Các Công Cụ',
    'position' => 2,
    'created_at' => '2025-11-12 18:28:33',
    'updated_at' => '2025-11-12 18:28:33',
  ],
  5 => [
    'id' => 8,
    'course_id' => 15,
    'title' => 'y',
    'position' => 1,
    'created_at' => '2025-11-14 18:52:23',
    'updated_at' => '2025-11-14 18:52:23',
  ],
  6 => [
    'id' => 9,
    'course_id' => 31,
    'title' => 'df',
    'position' => 3,
    'created_at' => '2025-11-15 17:55:42',
    'updated_at' => '2025-11-15 17:55:42',
  ],
  7 => [
    'id' => 10,
    'course_id' => 35,
    'title' => 'Phần 1: Giới thiệu',
    'position' => 1,
    'created_at' => '2025-11-16 06:08:29',
    'updated_at' => '2025-11-16 06:08:29',
  ],
  8 => [
    'id' => 11,
    'course_id' => 35,
    'title' => 'Phần 2: Biến và kiểu dữ liệu',
    'position' => 2,
    'created_at' => '2025-11-16 06:08:54',
    'updated_at' => '2025-11-16 06:08:54',
  ],
  9 => [
    'id' => 12,
    'course_id' => 35,
    'title' => 'Phần 3: Cấu trúc điều khiển và vòng lặp',
    'position' => 3,
    'created_at' => '2025-11-16 06:09:17',
    'updated_at' => '2025-11-16 06:09:17',
  ],
  10 => [
    'id' => 13,
    'course_id' => 35,
    'title' => 'Phần 4: Mảng',
    'position' => 4,
    'created_at' => '2025-11-16 06:09:32',
    'updated_at' => '2025-11-16 06:09:32',
  ],
  11 => [
    'id' => 14,
    'course_id' => 35,
    'title' => 'Phần 5: Hàm',
    'position' => 5,
    'created_at' => '2025-11-16 06:09:41',
    'updated_at' => '2025-11-16 06:09:41',
  ],
  12 => [
    'id' => 15,
    'course_id' => 35,
    'title' => 'test',
    'position' => 6,
    'created_at' => '2025-11-16 13:02:15',
    'updated_at' => '2025-11-16 13:02:15',
  ],
];
        
        // Insert data
        DB::table('sections')->insert($data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('sections', 'id'),
                    (SELECT MAX(id) FROM sections)
                )
            ");
        } catch (\Exception $e) {
            // Ignore if table doesn't have id column
        }
    }
}

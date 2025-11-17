<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryMediaDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('category_media')->truncate();
        
        $data = [
  0 => [
    'id' => 1,
    'category_id' => 1,
    'type' => 'thumbnail',
    'path' => 'lap-trinh.jpg',
    'created_at' => '2025-10-29 16:48:10',
    'updated_at' => '2025-10-29 16:48:10',
  ],
  1 => [
    'id' => 2,
    'category_id' => 2,
    'type' => 'thumbnail',
    'path' => 'thiet-ke.jpg',
    'created_at' => '2025-10-29 16:48:10',
    'updated_at' => '2025-10-29 16:48:10',
  ],
  2 => [
    'id' => 3,
    'category_id' => 3,
    'type' => 'thumbnail',
    'path' => 'khoa-hoc-du-lieu.jpg',
    'created_at' => '2025-10-29 16:48:10',
    'updated_at' => '2025-10-29 16:48:10',
  ],
  3 => [
    'id' => 4,
    'category_id' => 4,
    'type' => 'thumbnail',
    'path' => 'tri-tue-nhan-tao.jpg',
    'created_at' => '2025-10-29 16:48:10',
    'updated_at' => '2025-10-29 16:48:10',
  ],
  4 => [
    'id' => 5,
    'category_id' => 5,
    'type' => 'thumbnail',
    'path' => 'phat-trien-web.jpg',
    'created_at' => '2025-10-29 16:48:10',
    'updated_at' => '2025-10-29 16:48:10',
  ],
  5 => [
    'id' => 6,
    'category_id' => 6,
    'type' => 'thumbnail',
    'path' => 'phat-trien-di-dong.jpg',
    'created_at' => '2025-10-29 16:48:10',
    'updated_at' => '2025-10-29 16:48:10',
  ],
  6 => [
    'id' => 7,
    'category_id' => 7,
    'type' => 'thumbnail',
    'path' => 'marketing-so.jpg',
    'created_at' => '2025-10-29 16:48:10',
    'updated_at' => '2025-10-29 16:48:10',
  ],
  7 => [
    'id' => 8,
    'category_id' => 8,
    'type' => 'thumbnail',
    'path' => 'kinh-doanh.jpg',
    'created_at' => '2025-10-29 16:48:10',
    'updated_at' => '2025-10-29 16:48:10',
  ],
  8 => [
    'id' => 9,
    'category_id' => 9,
    'type' => 'thumbnail',
    'path' => 'an-ninh-mang.jpg',
    'created_at' => '2025-10-29 16:48:10',
    'updated_at' => '2025-10-29 16:48:10',
  ],
];
        
        // Insert data
        DB::table('category_media')->insert($data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('category_media', 'id'),
                    (SELECT MAX(id) FROM category_media)
                )
            ");
        } catch (\Exception $e) {
            // Ignore if table doesn't have id column
        }
    }
}

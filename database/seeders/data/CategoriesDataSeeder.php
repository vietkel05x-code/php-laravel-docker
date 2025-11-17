<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('categories')->truncate();
        
        $data = [
  0 => [
    'id' => 1,
    'name' => 'Lập trình',
    'slug' => 'lap-trinh',
    'description' => NULL,
    'image' => NULL,
    'parent_id' => NULL,
    'created_at' => '2025-10-15 13:08:10',
    'updated_at' => '2025-10-15 13:08:10',
  ],
  1 => [
    'id' => 2,
    'name' => 'Thiết kế',
    'slug' => 'thiet-ke',
    'description' => NULL,
    'image' => NULL,
    'parent_id' => NULL,
    'created_at' => '2025-10-15 13:08:10',
    'updated_at' => '2025-10-15 13:08:10',
  ],
  2 => [
    'id' => 3,
    'name' => 'Khoa học dữ liệu',
    'slug' => 'khoa-hoc-du-lieu',
    'description' => NULL,
    'image' => NULL,
    'parent_id' => NULL,
    'created_at' => '2025-10-29 16:46:37',
    'updated_at' => '2025-10-29 16:46:37',
  ],
  3 => [
    'id' => 4,
    'name' => 'Trí tuệ nhân tạo',
    'slug' => 'tri-tue-nhan-tao',
    'description' => NULL,
    'image' => NULL,
    'parent_id' => NULL,
    'created_at' => '2025-10-29 16:46:37',
    'updated_at' => '2025-10-29 16:46:37',
  ],
  4 => [
    'id' => 5,
    'name' => 'Phát triển web',
    'slug' => 'phat-trien-web',
    'description' => NULL,
    'image' => NULL,
    'parent_id' => NULL,
    'created_at' => '2025-10-29 16:46:37',
    'updated_at' => '2025-10-29 16:46:37',
  ],
  5 => [
    'id' => 6,
    'name' => 'Phát triển di động',
    'slug' => 'phat-trien-di-dong',
    'description' => NULL,
    'image' => NULL,
    'parent_id' => NULL,
    'created_at' => '2025-10-29 16:46:37',
    'updated_at' => '2025-10-29 16:46:37',
  ],
  6 => [
    'id' => 7,
    'name' => 'Marketing số',
    'slug' => 'marketing-so',
    'description' => NULL,
    'image' => NULL,
    'parent_id' => NULL,
    'created_at' => '2025-10-29 16:46:37',
    'updated_at' => '2025-10-29 16:46:37',
  ],
  7 => [
    'id' => 8,
    'name' => 'Kinh doanh',
    'slug' => 'kinh-doanh',
    'description' => NULL,
    'image' => NULL,
    'parent_id' => NULL,
    'created_at' => '2025-10-29 16:46:37',
    'updated_at' => '2025-10-29 16:46:37',
  ],
  8 => [
    'id' => 9,
    'name' => 'An ninh mạng',
    'slug' => 'an-ninh-mang',
    'description' => NULL,
    'image' => NULL,
    'parent_id' => NULL,
    'created_at' => '2025-10-29 16:46:37',
    'updated_at' => '2025-10-29 16:46:37',
  ],
];
        
        // Insert data
        DB::table('categories')->insert($data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('categories', 'id'),
                    (SELECT MAX(id) FROM categories)
                )
            ");
        } catch (\Exception $e) {
            // Ignore if table doesn't have id column
        }
    }
}
